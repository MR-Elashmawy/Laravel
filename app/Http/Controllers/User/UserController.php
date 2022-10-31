<?php

namespace App\Http\Controllers\User;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('users.profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        $oldImg = $user->image; // old image name

        if (request()->hasFile('image')) {
            $image = $request->file('image');
            $imgExt = $image->extension();
            $imgName = request()->title . time() . "." . $imgExt;
            $path = public_path('\images\users');
            $image->move($path, $imgName); //upload new image

            if ($oldImg !== 'default.png') { // don't remove default image
                $image_path =  public_path('images/users/' . $oldImg);
                if (file_exists($image_path)) {
                    @unlink($image_path); //delete old image
                }
            }

        } else { //user doesn't update image
            $imgName = $oldImg;
        }

        $formData = request()->all();
        if($formData['current_password'] || $formData['password']){

            $oldPassword = $formData['current_password'];
            $newPassword = $formData['password'];
    
            if(!$oldPassword || !$newPassword){
                return to_route('users.edit', $id)->with('error', 'Please enter both passwords field');
            }

            if (!Hash::check($oldPassword, $user->password)) {
                return to_route('users.edit', $id)->with('error', 'current password incorrect');
            }

            $user->password = Hash::make($newPassword); //update password in database

        } // user want to change password

        $user->name = request()->name;
        $user->email = request()->email;
        $user->image =  $imgName;
        $user->save();

        //show success message
        return to_route('users.edit', $id)->with('message', 'User updated successfully');
    } // end of update

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
