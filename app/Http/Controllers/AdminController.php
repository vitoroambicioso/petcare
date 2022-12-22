<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if(!empty($request->all())) {

            if(isset($request->admin) && !is_null($request->admin)) {
                $adminKey = $request->admin;
                if($adminKey == getenv('ADMIN_KEY')) {
                    $admin = new Admin;
                    $admin->name = $request->name;
                    $admin->email = $request->email;
                    $admin->photo = $request->photo;
                    $admin->password = bcrypt($request->password);
                    $admin->save();

                    return response()->json([
                        "message" => "admin record created"
                    ], 201); 
                } else {
                    return response()->json([
                        "message" => "access denied by admin key wrong"
                    ], 403);
                }
            } else {
                return response()->json([
                    "message" => "bad request"
                ], 400);
            }
        }

    } 

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAdmin(Request $request, $id)
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
                        if (Admin::where('id', $id)->exists()) {
                            $admin = Admin::find($id);

                            return response()->json([
                                "admin" => $Admin,
                            ], 200);
                        } else {
                            return response()->json([
                                "message" => "admin not found"
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

    public function getAlladmins()
    {
        $admins = Admin::get()->toJson(JSON_PRETTY_PRINT);
        return response($admins, 200);
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

            $tokenValid = $this->validacaoJwt($request);
                
            switch($tokenValid) {
                    
                case 1:
                    if($jwtPayload->id == $id) {

                        if(isset($request->newPassword)){
                            
                            $credentials = $request->only('email', 'password');
                            
                            if (Auth::attempt($credentials)) {
                                $admin = Admin::find($id);
                                $admin->name = is_null($request->name) ? $admin->name : $request->name;
                                $admin->photo = is_null($request->photo) ? $admin->photo : $request->photo;
                                $admin->password = bcrypt(is_null($request->newPassword) ? $admin->password : $request->newPassword);
                                $admin->update();
                                
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
                                $admin = Admin::find($id);
                                $admin->name = is_null($request->name) ? $admin->name : $request->name;
                                $admin->photo = is_null($request->photo) ? $admin->photo : $request->photo;
                                $admin->password = bcrypt(is_null($request->password) ? $admin->password : $request->password);
                                $admin->update();
                                
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
                        "message" => "admin not found"
                    ], 404);
                    break;
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

            $tokenValid = $this->validacaoJwt($request);
                
            switch($tokenValid) {
                        
                case 1:
                    if($jwtPayload->id == $id) {
                        $admin = Admin::find($id);
                        $admin->delete();
                                
                        return response()->json([
                            "token" => $request->token,
                            "message" => "records deleted"
                        ], 202);
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
                    'id' => Auth::admin()->id
                ];

                $jwtHeader = json_encode($jwtHeader);
                $jwtHeader = base64_encode($jwtHeader);

                $jwtPayload = json_encode($jwtPayload);
                $jwtPayload = base64_encode($jwtPayload);

                $jwtSignature = hash_hmac('sha256',"$jwtHeader.$jwtPayload", getenv('JWT_KEY'),true);
                $jwtSignature = base64_encode($jwtSignature);

                $token = "$jwtHeader.$jwtPayload.$jwtSignature";
                
                $admin = $this->getadminNoRqt(Auth::admin()->id);
                
                return response()->json([
                    "token" => $token,
                    "photo" => $admin->photo,
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
    public function getadminNoRqt($id)
    {
        if (admin::where('id', $id)->exists()) {
            $admin = admin::find($id);

            return $admin;
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
                           if(admin::find($jwtPayload->id)) {
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