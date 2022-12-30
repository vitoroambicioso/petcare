<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denuncia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use Carbon\Carbon;

class DenunciaController extends Controller
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
    public function create(Request $request)
    {
        if(isset($request->token)) {
            
            if(!empty($request->all())) {

                $tokenParts = explode(".", $request->token);
            
                $tokenHeader = base64_decode($tokenParts[0]);
                $jwtHeader = json_decode($tokenHeader);
                $tokenPayload = base64_decode($tokenParts[1]);
                $jwtPayload = json_decode($tokenPayload);
    
                $tokenValid = $this->validacaoJwt($request);

                switch($tokenValid) {

                    case 1:

                        if(isset($request->picture2) == TRUE && isset($request->picture3) == FALSE) {
                            $denuncia = new Denuncia;
                            $denuncia->idUsuario = $jwtPayload->id;
                            $denuncia->tipo = $request->tipo;
                            $denuncia->cor = $request->cor;
                            $denuncia->rua = $request->rua;
                            $denuncia->bairro = $request->bairro;
                            $denuncia->pontoDeReferencia = $request->pontoDeReferencia;
                            $denuncia->picture1 = $request->picture1;
                            $denuncia->picture2 = $request->picture2;
                            $denuncia->descricao = $request->descricao;
                            $denuncia->save();
            
                            return response()->json([
                                "message" => "denuncia record created"
                            ], 201);
                        } else if(isset($request->picture3)) {
                            $denuncia = new Denuncia;
                            $denuncia->idUsuario = $jwtPayload->id;
                            $denuncia->tipo = $request->tipo;
                            $denuncia->cor = $request->cor;
                            $denuncia->rua = $request->rua;
                            $denuncia->bairro = $request->bairro;
                            $denuncia->pontoDeReferencia = $request->pontoDeReferencia;
                            $denuncia->picture1 = $request->picture1;
                            $denuncia->picture2 = $request->picture2;
                            $denuncia->picture3 = $request->picture3;
                            $denuncia->descricao = $request->descricao;
                            $denuncia->save();
            
                            return response()->json([
                                "message" => "denuncia record created"
                            ], 201);
                        } else {
                            $denuncia = new Denuncia;
                            $denuncia->idUsuario = $jwtPayload->id;
                            $denuncia->tipo = $request->tipo;
                            $denuncia->cor = $request->cor;
                            $denuncia->rua = $request->rua;
                            $denuncia->bairro = $request->bairro;
                            $denuncia->pontoDeReferencia = $request->pontoDeReferencia;
                            $denuncia->picture1 = $request->picture1;
                            $denuncia->descricao = $request->descricao;
                            $denuncia->save();
        
                            return response()->json([
                                "message" => "denuncia record created"
                            ], 201);
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
    public function getDenuncia(Request $request, $id)
    {
        if(!empty($request->all())) {
            
            $tokenParts = explode(".", $request->token);
            
            $tokenHeader = base64_decode($tokenParts[0]);
            $jwtHeader = json_decode($tokenHeader);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);

            if(Admin::where('email', $jwtPayload->email)->exists()) {

                $isAdmin = new AdminController;
                $tokenValidAdmin = $isAdmin->validacaoJwt($request);
    
                switch($tokenValidAdmin) {

                    case 1:
                        if (Denuncia::where('idUsuario', $id)->exists()) {

                            $denuncia = Denuncia::where('idUsuario', $id)->get();
                            return response()->json(
                            $denuncia, 200);
                        } else {
                            return response()->json([
                                "message" => "denuncia not found",
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
                
                        if (Denuncia::where('idUsuario', $jwtPayload->id)->exists()) {
                            
                            $denuncia = Denuncia::where('idUsuario', $jwtPayload->id)->get();
                            return response()->json(
                                $denuncia, 200);
                        } else {
                            return response()->json([
                            "message" => "denuncia not found",
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

    public function getAllDenuncias(Request $request)
    {

        if(!empty($request->all())) {
            
            $tokenParts = explode(".", $request->token);
            
            $tokenHeader = base64_decode($tokenParts[0]);
            $jwtHeader = json_decode($tokenHeader);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);

            if(Admin::where('email', $jwtPayload->email)->exists()) {

                $isAdmin = new AdminController;
                $tokenValidAdmin = $isAdmin->validacaoJwt($request);
    
                switch($tokenValidAdmin) {
                    case 1:
                        $denuncias = Denuncia::get();
                        return response()->json([
                            $denuncias,
                        ], 200);
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
                    "message" => "admin not found"
                ], 404);
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

                        if (Denuncia::where('id', $id)->exists()) {
    
                            $denuncia = Denuncia::find($id);

                            $denuncia->tipo = is_null($request->tipo) ? $denuncia->tipo : $request->tipo;
                            $denuncia->cor = is_null($request->cor) ? $denuncia->cor : $request->cor;
                            $denuncia->rua = is_null($request->rua) ? $denuncia->rua : $request->rua;
                            $denuncia->bairro = is_null($request->bairro) ? $denuncia->bairro : $request->bairro;
                            $denuncia->pontoDeReferencia = is_null($request->pontoDeReferencia) ? $denuncia->pontoDeReferencia : $request->pontoDeReferencia;
                            $denuncia->descricao = is_null($request->descricao) ? $denuncia->descricao : $request->descricao;
                            $denuncia->update();
            
                            return response()->json([
                                "message" => "records updated successfully by admin"
                            ], 200);
                        } else {
                            return response()->json([
                                "message" => "denuncia not found"
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
    
                        if (Denuncia::where('id', $id)->exists()) {
    
                            $denuncia = Denuncia::find($id);
    
                            if($jwtPayload->id == $denuncia->idUsuario) {
    
                                $denuncia->tipo = is_null($request->tipo) ? $denuncia->tipo : $request->tipo;
                                $denuncia->cor = is_null($request->cor) ? $denuncia->cor : $request->cor;
                                $denuncia->rua = is_null($request->rua) ? $denuncia->rua : $request->rua;
                                $denuncia->bairro = is_null($request->bairro) ? $denuncia->bairro : $request->bairro;
                                $denuncia->pontoDeReferencia = is_null($request->pontoDeReferencia) ? $denuncia->pontoDeReferencia : $request->pontoDeReferencia;
                                $denuncia->descricao = is_null($request->descricao) ? $denuncia->descricao : $request->descricao;
                                $denuncia->update();
                
                                return response()->json([
                                    "message" => "records updated successfully"
                                ], 200);
                            } else {
                                return response()->json([
                                    "message" => "user does not have permission to modify this denuncia"
                                ], 403);
                            }
                        } else {
                            return response()->json([
                                "message" => "denuncia not found"
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

                        if(Denuncia::where('id', $id)->exists()) {

                            $credentials = ['email' => $jwtPayload->email, 'password' => $request->password];

                            if(Auth::guard('admin')->attempt($credentials)) {
                                if($request->adminKey == getenv('ADMIN_KEY')) {
                                    
                                    $denuncia = Denuncia::find($id);
                                    $denuncia->delete();

                                    return response()->json([
                                        "message" => "denuncia records deleted"
                                    ], 202);
                                } else {
                                    return response()->json([
                                        "message" => "access denied by admin key wrong"
                                    ], 404);
                                }
                            } else {
                                return response()->json([
                                    "message" => "login attempt failed"
                                ], 404);
                            }
    
                            $denuncia = Denuncia::find($id);
                            $denuncia->delete();
                    
                            return response()->json([
                                "message" => "denuncia records deleted"
                            ], 202);
                            } else {
                                return response()->json([
                                    "message" => "user does not have permission to delete this denuncia"
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
    
                        if(Denuncia::where('id', $id)->exists()) {
    
                            $denuncia = Denuncia::find($id);
                            
                            if($jwtPayload->id == $denuncia->idUsuario) {
                                $denuncia->delete();
                    
                                return response()->json([
                                $denuncia,
                                "message" => "denuncia records deleted"
                                ], 202);
                            } else {
                                return response()->json([
                                    "message" => "user does not have permission to delete this denuncia"
                                ], 403);
                            }
                        } else {
                            return response()->json([
                            "message" => "denuncia not found"
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
