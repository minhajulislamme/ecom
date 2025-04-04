<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        $id = Auth::id();
        $adminData = User::find($id);
        return view('backend.index', compact('adminData'));
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Successfully Logged Out',
            'alert-type' => 'success'  // 'success' or 'error' only
        );

        return redirect('/admin/login')->with($notification);
    }

    public function AdminLogin()
    {
        return view('backend.profile.admin_login');
    }

    public function AdminLoginStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'password.required' => 'Password is required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $notification = array(
                'message' => 'Successfully logged in',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.dashboard')->with($notification);
        } else {
            $notification = array(
                'message' => 'Invalid email or password',
                'alert-type' => 'error'
            );
            return redirect()->route('admin.login')->with($notification);
        }
    }

    public function AdminProfile()
    {
        $id = Auth::id();
        $adminData = User::find($id);
        return view('backend.profile.admin_profile', compact('adminData'));
    }

    public function AdminProfileUpdate(Request $request)
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        $adminData->name = $request->name;
        $adminData->email = $request->email;
        $adminData->phone = $request->phone;
        $adminData->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');

            // Create directory if doesn't exist
            $uploadPath = public_path('upload/admin_images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete old image if exists
            if ($adminData->photo) {
                $oldImage = $uploadPath . '/' . $adminData->photo;
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            // Generate unique filename
            $filename = date('YmdHi') . uniqid() . '.webp';

            // Create image manager instance
            $manager = new ImageManager(new Driver());

            // Process and save the image
            $image = $manager->read($file);
            $image->resize(800, 800) // Fixed size 800x800
                ->toWebp(70)  // Convert to WebP with 70% quality
                ->save($uploadPath . '/' . $filename);

            $adminData['photo'] = $filename;
        }

        $adminData->save();

        $notification = array(
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function AdminPassword()
    {
        $id = Auth::id();
        $adminData = User::find($id);
        return view('backend.profile.admin_password', compact('adminData'));
    }

    public function AdminPasswordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        // Check if the current password matches
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            $notification = array(
                'message' => 'Current password does not match our records',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        // Update the password
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        $notification = array(
            'message' => 'Password Updated Successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }
}
