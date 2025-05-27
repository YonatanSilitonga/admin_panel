<?php

namespace App\Http\Controllers;

use App\Exports\OrangTuaExport;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OrangTuaController extends Controller
{
    /**
     * Display a listing of all parents.
     */
    public function index(Request $request)
    {
        $kelasId = $request->input('kelas');
        $status = $request->input('status', 'aktif'); // Default ke 'aktif'
        $search = $request->input('search');
    
        $kelasList = Kelas::all();
    
        // Update semua status orangtua terlebih dahulu
        $this->updateAllParentStatuses();
    
        $query = OrangTua::with(['siswa.kelas', 'user']);
    
        // Filter berdasarkan kelas anak
        if ($kelasId) {
            $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                $siswaQuery->where('id_kelas', $kelasId);
            });
        }
    
        // Filter berdasarkan status
        if ($status && $status !== 'semua') {
            $query->where('orangtua.status', $status);
        }
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%");
            });
        }
    
        $orangTuaList = $query->orderBy('nama_lengkap')->get();
        
        // Filter ulang relasi siswa jika kelas disaring
        if ($kelasId) {
            foreach ($orangTuaList as $orangTua) {
                $filteredSiswa = $orangTua->siswa->filter(function ($siswa) use ($kelasId) {
                    return $siswa->id_kelas == $kelasId;
                });
                $orangTua->setRelation('siswa', $filteredSiswa);
            }
        }
    
        return view('admin.pages.orang_tua.manajemen_data_orang_tua', compact('orangTuaList', 'kelasList'));
    }

    /**
     * Update all parent statuses based on their children
     */
    private function updateAllParentStatuses()
    {
        $allParents = OrangTua::all();
        foreach ($allParents as $parent) {
            $parent->updateStatusBasedOnChildren();
        }
    }

    /**
     * Export data orang tua to PDF.
     */
    public function exportPdf(Request $request)
    {
        $kelasId = $request->input('kelas');
        $status = $request->input('status', 'aktif');
        $search = $request->input('search');
        
        // Update status terlebih dahulu
        $this->updateAllParentStatuses();
        
        $query = OrangTua::with(['siswa.kelas', 'user']);
        
        // Filter berdasarkan kelas anak
        if ($kelasId) {
            $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                $siswaQuery->where('id_kelas', $kelasId);
            });
        }
        
        // Filter berdasarkan status
        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }
        
        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%")
                  ->orWhere('pekerjaan', 'like', "%{$search}%");
            });
        }
        
        $orangTuaList = $query->orderBy('nama_lengkap')->get();
        
        // Filter ulang relasi siswa jika kelas disaring
        if ($kelasId) {
            foreach ($orangTuaList as $orangTua) {
                $filteredSiswa = $orangTua->siswa->filter(function ($siswa) use ($kelasId) {
                    return $siswa->id_kelas == $kelasId;
                });
                $orangTua->setRelation('siswa', $filteredSiswa);
            }
        }

        $pdf = Pdf::loadView('exports.orangtua_pdf', compact('orangTuaList'));
        return $pdf->download('data_orang_tua.pdf');
    }

    /**
     * Export data orang tua to Excel.
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new OrangTuaExport(
            $request->input('kelas'), 
            $request->input('status', 'aktif'),
            $request->input('search')
        ), 'data_orang_tua.xlsx');
    }

    /**
     * Show the form for creating a new parent.
     */
    public function create()
    {
        return view('admin.pages.orang_tua.tambah_orang_tua');
    }

    /**
     * Store a newly created parent in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|min:6|max:255|unique:users,username',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
            ],
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|numeric|digits_between:10,15',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'username.required' => 'Username harus diisi',
            'username.min' => 'Username minimal 6 karakter',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung huruf dan angka',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'nomor_telepon.numeric' => 'Nomor telepon harus berupa angka',
            'nomor_telepon.digits_between' => 'Nomor telepon harus terdiri dari 10-15 digit',
        ]);

        DB::beginTransaction();
        try {
            // Create user account first
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'id_role' => 2, // Role id for parent
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user()->username ?? 'system',
            ]);

            // Create parent record with pending status
            $orangTua = OrangTua::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'nomor_telepon' => $request->nomor_telepon,
                'status' => OrangTua::STATUS_PENDING,
                'dibuat_pada' => now(),
                'dibuat_oleh' => Auth::user()->username ?? 'system',
            ]);

            DB::commit();

            return redirect()->route('orang-tua.index', ['status' => 'semua'])
                ->with('success', 'Data orang tua berhasil ditambahkan dengan status Pending.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data orang tua: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified parent.
     */
    public function show($id)
    {
        $orangTua = OrangTua::with(['user', 'siswa.kelas'])->find($id);
        
        if (!$orangTua) {
            return redirect()->route('orang-tua.index')
                ->with('error', 'Data orang tua tidak ditemukan.');
        }
        
        // Update status before displaying
        $orangTua->updateStatusBasedOnChildren();
        
        return view('admin.pages.orang_tua.detail_orang_tua', compact('orangTua'));
    }

    /**
     * Show the form for editing the specified parent.
     */
    public function edit($id)
    {
        $orangTua = OrangTua::with('user', 'siswa.kelas')->findOrFail($id);
        
        // Update status before editing
        $orangTua->updateStatusBasedOnChildren();
    
        return view('admin.pages.orang_tua.edit_orang_tua', compact('orangTua'));
    }

    /**
     * Update the specified parent in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'pekerjaan' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|numeric|digits_between:10,15',
            'status' => 'nullable|in:aktif,tidak_aktif,pending', // Validasi status manual
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nomor_telepon.numeric' => 'Nomor telepon harus berupa angka',
            'nomor_telepon.digits_between' => 'Nomor telepon harus terdiri dari 10-15 digit',
            'status.in' => 'Status harus berupa aktif, tidak_aktif, atau pending',
        ]);

        DB::beginTransaction();
        try {
            $orangTua = OrangTua::findOrFail($id);
        
            // Update parent data
            $orangTua->update([
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'nomor_telepon' => $request->nomor_telepon,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => Auth::user()->username ?? 'system',
            ]);
            
            // Check if manual status override is provided
            if ($request->filled('status')) {
                // Manual status override
                $orangTua->status = $request->status;
                $orangTua->save();
                
                $message = 'Data orang tua berhasil diperbarui dengan status manual: ' . ucfirst($request->status);
            } else {
                // Auto update status based on children
                $orangTua->updateStatusBasedOnChildren();
                $message = 'Data orang tua berhasil diperbarui dengan status otomatis.';
            }
        
            DB::commit();
            
            // Redirect to the same status filter that was active before the update
            $redirectStatus = request('status', 'aktif');
        
            return redirect()->route('orang-tua.index', ['status' => $redirectStatus])
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data orang tua: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified parent from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $orangTua = OrangTua::with('siswa', 'user')->findOrFail($id);
            
            // Check if parent has children
            if ($orangTua->siswa->count() > 0) {
                // Remove parent association from all children
                Siswa::where('id_orangtua', $id)
                    ->update([
                        'id_orangtua' => null,
                        'diperbarui_pada' => now(),
                        'diperbarui_oleh' => Auth::user()->username ?? 'system',
                    ]);
            }
            
            // Delete user account
            if ($orangTua->user) {
                $orangTua->user->delete();
            }
            
            // Delete parent record
            $orangTua->delete();
            
            DB::commit();
            
            return redirect()->route('orang-tua.index')
                ->with('success', 'Data orang tua berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus data orang tua: ' . $e->getMessage());
        }
    }
    
    /**
     * Display parents by class for their children.
     */
    public function showByKelas($id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);
        
        // Update status terlebih dahulu
        $this->updateAllParentStatuses();
        
        $orangTuaList = OrangTua::whereHas('siswa', function($query) use ($id_kelas) {
            $query->where('id_kelas', $id_kelas);
        })->with(['siswa' => function($query) use ($id_kelas) {
            $query->where('id_kelas', $id_kelas)->with('kelas');
        }, 'user'])->get();
        
        return view('admin.pages.orang_tua.kelas_orang_tua', compact('orangTuaList', 'kelas'));
    }

    /**
     * Get all parents for dropdown.
     */
    public function getList(Request $request)
    {
        $status = $request->input('status', 'aktif'); // Default ke aktif
        
        // Update all parent statuses first
        $this->updateAllParentStatuses();
        
        $query = OrangTua::orderBy('nama_lengkap');
        
        // Filter by status if provided and not 'semua'
        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }
        
        $orangTuaList = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $orangTuaList,
        ], 200);
    }

    public function getAnak($id)
    {
        $orangTua = OrangTua::with('siswa.kelas')->find($id);
    
        if (!$orangTua) {
            return response()->json([], 404);
        }
    
        return response()->json($orangTua->siswa);
    }
    
    /**
     * Update parent status based on children.
     */
    public function updateStatus($id)
    {
        try {
            $orangTua = OrangTua::findOrFail($id);
            $oldStatus = $orangTua->status;
            
            $orangTua->updateStatusBasedOnChildren();
            
            // Refresh the model from database to get updated status
            $orangTua->refresh();
            
            return response()->json([
                'success' => true,
                'message' => 'Status orang tua berhasil diperbarui otomatis',
                'old_status' => $oldStatus,
                'new_status' => $orangTua->status,
                'status_badge' => $orangTua->getStatusBadgeHtml()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manual status update for parent
     */
    public function updateStatusManual(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,tidak_aktif,pending'
        ]);

        try {
            $orangTua = OrangTua::findOrFail($id);
            $oldStatus = $orangTua->status;
            
            $orangTua->update([
                'status' => $request->status,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => Auth::user()->username ?? 'system',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status orang tua berhasil diperbarui secara manual',
                'old_status' => $oldStatus,
                'new_status' => $orangTua->status,
                'status_badge' => $orangTua->getStatusBadgeHtml()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update all parent statuses based on their children.
     */
    public function bulkUpdateStatus()
    {
        try {
            $allParents = OrangTua::all();
            $updatedCount = 0;
            
            foreach ($allParents as $parent) {
                $oldStatus = $parent->status;
                $parent->updateStatusBasedOnChildren();
                
                if ($oldStatus !== $parent->status) {
                    $updatedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Status {$updatedCount} orang tua berhasil diperbarui otomatis",
                'updated_count' => $updatedCount,
                'total_count' => $allParents->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}