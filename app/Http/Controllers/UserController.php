<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Denuncia;
use App\Http\Controllers\AdminController;
use Auth;
use Session;
use Carbon\Carbon;

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
    /*public function create()
    {
        //
    }
    */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!empty($request->all())) {
            
            if(User::where('email', $request->email)->exists() == FALSE) {
                
                if(Admin::where('email', $request->email)->exists() == FALSE) {
                    $user = new User;
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->photo = $request->photo;
                    $user->password = bcrypt($request->password);
                    $user->save();
    
                    return response()->json([
                        "message" => "user record created"
                    ], 201);
                } else {
                    return response()->json([
                        "message" => "user is admin"
                    ], 403);
                }
            } else {
                return response()->json([
                    "message" => "user already exists"
                ], 403);
            }
        } else {
                return response()->json([
                    "message" => "bad request"
                ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request, $id)
    {
        if(!empty($request->all())) {

            $tokenParts = explode(".", $request->token);  
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);

            $tokenValid = $this->validacaoJwt($request);
            
            switch($tokenValid) {

                case 1:
                    /**
                    * verifica id do token
                    */
                    if($jwtPayload->id == $id) {
                        if (User::where('id', $id)->exists()) {
                            $user = User::find($id);

                            return response()->json([
                                "user" => $user,
                            ], 200);
                        } else {
                            return response()->json([
                                "message" => "user not found"
                            ], 404);
                        }
                    } else {
                        return response()->json([
                            "message" => "id not found"
                        ], 404);
                    }
                    break;
                case 2:
                    return response()->json([
                        "message" => "token has expired",
                    ], 403);
                    break;
                case 3:
                    return response()->json([
                        "message" => "invalid token",
                    ], 403);
                    break;
                case 4:
                    return response()->json([
                        "message" => "invalid token structure"
                    ], 403);
                    break;
                case 5:
                    return response()->json([
                        "message" => "token does not exist"
                    ], 403);
                    break;
            }
        } else {
            return response()->json([
                "message" => "bad request"
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if(!empty($request->all())) {
           
            $tokenParts = explode(".", $request->token);  
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);
            
            if(Admin::where('email', $jwtPayload->email)->exists()) {

                $isAdmin = new AdminController;
                $tokenValidAdmin = $isAdmin->validacaoJwt($request);

                switch($tokenValidAdmin) {

                    case 1:
                        $credentials = ['email' => $jwtPayload->email, 'password' => $request->password];
                            
                        if (Auth::guard('admin')->attempt($credentials)) {
                            
                            if(User::where('email', $request->oldEmail)->exists()) {

                                if($request->oldEmail == $request->email) {
                                    $user = User::find($id);
                                    $user->name = is_null($request->name) ? $user->name : $request->name;
                                    $user->email = is_null($request->email) ? $user->email : $request->email;
                                    $user->photo = is_null($request->photo) ? $user->photo : $request->photo;
                                    $user->update();
                                    
                                    return response()->json([
                                        "message" => "user records updated successfully by admin"
                                    ], 200);
                                } else {
                                    return response()->json([
                                        "message" => "user's email already exists",
                                    ], 403);
                                }
                            } else {
                                return response()->json([
                                    "message" => "user not found",
                                ], 404);
                            }
                        } else {
                            return response()->json([
                                "message" => "login admin attempt failed",
                            ], 404);
                        }
                        break;
                        case 2:
                            return response()->json([
                                "message" => "token has expired",
                            ], 403);
                            break;
                        case 3:
                            return response()->json([
                                "message" => "invalid token",
                            ], 403);
                            break;
                        case 4:
                            return response()->json([
                                "message" => "invalid token structure"
                            ], 403);
                            break;
                        case 5:
                            return response()->json([
                                "message" => "token does not exist"
                            ], 403);
                            break;
                        case 6:
                            return response()->json([
                                "message" => "admin not found"
                            ], 404);
                            break;
                }
            } else {

                $tokenValid = $this->validacaoJwt($request);

                switch($tokenValid) {
                    
                    case 1:                          
                        if($jwtPayload->id == $id) {

                            if(isset($request->newPassword)){
                                
                                $credentials = $request->only('email', 'password');
                                
                                if (Auth::attempt($credentials)) {
                                    $user = User::find($id);
                                    $user->name = is_null($request->name) ? $user->name : $request->name;
                                    $user->photo = is_null($request->photo) ? $user->photo : $request->photo;
                                    $user->password = bcrypt(is_null($request->newPassword) ? $user->password : $request->newPassword);
                                    $user->update();
                                    
                                    return response()->json([
                                        "message" => "records updated successfully with new passowrd"
                                    ], 200);
                                } else {
                                    return response()->json([
                                        "message" => "login attempt failed"
                                    ], 404);
                                }
                            } else {
    
                                $credentials = $request->only('email', 'password');
                                
                                if (Auth::attempt($credentials)) {
                                    $user = User::find($id);
                                    $user->name = is_null($request->name) ? $user->name : $request->name;
                                    $user->photo = is_null($request->photo) ? $user->photo : $request->photo;
                                    $user->password = bcrypt(is_null($request->password) ? $user->password : $request->password);
                                    $user->update();
                                    
                                    return response()->json([
                                        "message" => "records updated successfully with old password"
                                    ], 200);
                                } else {
                                    return response()->json([
                                        "message" => "login attempt failed"
                                    ], 404);
                                }
                            }
                        } else {
                            return response()->json([
                                "message" => "id not found"
                            ], 403);
                        }
                        break;
                    case 2:
                        return response()->json([
                            "message" => "token has expired",
                        ], 403);
                        break;
                    case 3:
                        return response()->json([
                            "message" => "invalid token",
                        ], 403);
                        break;
                    case 4:
                        return response()->json([
                            "message" => "invalid token structure"
                        ], 403);
                        break;
                    case 5:
                        return response()->json([
                            "message" => "token does not exist"
                        ], 403);
                        break;
                    case 6:
                        return response()->json([
                            "message" => "user not found"
                        ], 404);
                        break;
                }
            }
        } else {
            return response()->json([
                "message" => "bad request"
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {

        if(!empty($request->all())) {
            
            $tokenParts = explode(".", $request->token);  
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);

            if(Admin::where('email', $jwtPayload->email)->exists()) {

                $isAdmin = new AdminController;
                $tokenValidAdmin = $isAdmin->validacaoJwt($request);

                switch($tokenValidAdmin) {

                    case 1:
                        $credentials = ['email' => $jwtPayload->email, 'password' => $request->password];
                            
                        if (Auth::guard('admin')->attempt($credentials)) {

                            if($request->adminKey == getenv('ADMIN_KEY')) {

                                if(User::where('id', $id)->exists()) {

                                    Denuncia::where('idUsuario', $id)->update([
                                        'idUsuario' => null,
                                    ]);

                                    $user = User::find($id);
                                    $user->delete();
    
                                    return response()->json([
                                        "message" => "user records deleted by admin"
                                    ], 202);
                                } else {
                                    return response()->json([
                                        "message" => "id not found"
                                    ], 404);
                                }
                            } else {
                                return response()->json([
                                    "message" => "access denied by admin key wrong"
                                ], 404);
                            }
                        } else {
                            return response()->json([
                                "message" => "login admin attempt failed"
                            ], 403);
                        }
                        break;
                            case 2:
                        return response()->json([
                            "message" => "token has expired",
                        ], 403);
                        break;
                    case 3:
                        return response()->json([
                            "message" => "invalid token",
                        ], 403);
                        break;
                    case 4:
                        return response()->json([
                            "message" => "invalid token structure"
                        ], 403);
                        break;
                    case 5:
                        return response()->json([
                            "message" => "token does not exist"
                        ], 403);
                        break;
                    case 6:
                        return response()->json([
                            "message" => "admin not found"
                        ], 404);
                        break;
                }
            } else {

                $tokenValid = $this->validacaoJwt($request);
                
                switch($tokenValid) {
                            
                    case 1:
                        if($jwtPayload->id == $id) {

                            Denuncia::where('idUsuario', $id)->update([
                                'idUsuario' => null,
                            ]);

                            $user = User::find($id);
                            $user->delete();
                                    
                            return response()->json([
                                "message" => "user records deleted"
                            ], 202);
                        } else {
                            return response()->json([
                                "message" => "id not found"
                            ], 404);
                        }
                        break;
                    case 2:
                        return response()->json([
                            "message" => "token has expired",
                        ], 403);
                        break;
                    case 3:
                        return response()->json([
                            "message" => "invalid token",
                        ], 403);
                        break;
                    case 4:
                        return response()->json([
                            "message" => "invalid token structure"
                        ], 403);
                        break;
                    case 5:
                        return response()->json([
                            "message" => "token does not exist"
                        ], 403);
                        break;
                }
            }
        } else {
            return response()->json([
                "message" => "bad request"
            ], 400);
        }
    }
    

    public function login(Request $request) {
        
        if(!empty($request->all())) {
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);

    
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {

                $timeNow = time();
                $expirationTime = $timeNow + 60*60;

                $jwtHeader = [
                    'alg' => 'HS256',
                    'typ' => 'JWT'
                ];
                $jwtPayload = [
                    'exp' => $expirationTime,
                    'iss' => 'petcarebackend',
                    'id' => Auth::user()->id,
                    'email' => $request->email
                ];

                $jwtHeader = json_encode($jwtHeader);
                $jwtHeader = base64_encode($jwtHeader);

                $jwtPayload = json_encode($jwtPayload);
                $jwtPayload = base64_encode($jwtPayload);

                $jwtSignature = hash_hmac('sha256',"$jwtHeader.$jwtPayload", getenv('JWT_KEY'),true);
                $jwtSignature = base64_encode($jwtSignature);

                $token = "$jwtHeader.$jwtPayload.$jwtSignature";
                
                $user = $this->getUserNoRqt(Auth::user()->id);
                
                return response()->json([
                    "token" => $token,
                    "photo" => $user->photo,
                    "message" => "successfully logged in"
                ], 200);
            } else {
                return response()->json([
                    "message" => "login attempt failed"
                ], 404);
            }
        } else {
            return response()->json([
                "message" => "bad request"
            ], 400);
        }
    }

    /**
     * funcao get sem request
     */
    public function getUserNoRqt($id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);

            return $user;
        }
    }
    
    /**
     * funcao para validar o token JWT
     */
    public function validacaoJwt(Request $request)
    {
        if(isset($request->token)) {

            $tokenParts = explode(".", $request->token);
            
            $tokenHeader = base64_decode($tokenParts[0]);
            $jwtHeader = json_decode($tokenHeader);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);
            
            /**
             * verifica estrutura do token jwt
             */
            if (sizeof($tokenParts)==3) {
                
                /**
                 * verifica o tempo de expiracao do token
                 */
                if(!empty($jwtPayload->exp) && is_null($jwtPayload->id) == FALSE) {
                    
                    $expiration = Carbon::createFromTimestamp(json_decode($tokenPayload)->exp);
                    $tokenExpired = (Carbon::now()->diffInSeconds($expiration, false) < 0);

                    if($tokenExpired==false) {
                                
                        $jwtSignatureValid = hash_hmac('sha256',"$tokenParts[0].$tokenParts[1]", getenv('JWT_KEY'),true);
                        $jwtSignatureValid = base64_encode($jwtSignatureValid);
                        
                        $tokenSignature = $tokenParts[2];

                        /**
                         * verifica signature do token
                         */
                        if($tokenSignature == $jwtSignatureValid) {
                           if(User::find($jwtPayload->id)) {
                                return 1;
                           } else {
                                return 6;
                           }
                        } else {
                            return 3;
                        }
                    } else {
                        return 2;
                    }
                } else {
                    return 3;
                }
            } else {
                return 4;
            }
        } else {
            return 5;
        }
    }
}

