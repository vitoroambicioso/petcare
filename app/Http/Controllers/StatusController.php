<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denuncia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Status;
use App\Models\Admin;
use Carbon\Carbon;

class StatusController extends Controller
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
        if(!empty($request->all())) {
            
            $tokenParts = explode(".", $request->token);
            
            $tokenHeader = base64_decode($tokenParts[0]);
            $jwtHeader = json_decode($tokenHeader);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);

            $isAdmin = new AdminController;
            $tokenValidAdmin = $isAdmin->validacaoJwt($request);
    
            switch($tokenValidAdmin) {

                case 1:
                    if(Denuncia::where('id', $request->idDenuncia)->exists() == TRUE && Status::where('idDenuncia', $request->idDenuncia)->exists() == FALSE) {
                        if($request->admin == "oculto") {

                            $status = new Status();
                            $denuncia = Denuncia::find($request->idDenuncia);
                            $admin = Admin::find($jwtPayload->id);
                            $status->idAdmin = $admin->id;
                            $status->idDenuncia = $request->idDenuncia;
                            $status->status = $request->status;
                            $status->admin = "oculto";
                            $status->org = $admin->org;
                            $status->message = $request->message;
                            $status->save();
    
                            return response()->json([
                                "message" => "status record created"
                            ], 201);
                        } else if($request->admin == "ativo") {
    
                            $status = new Status();
                            $denuncia = Denuncia::find($request->idDenuncia);
                            $admin = Admin::find($jwtPayload->id);
                            $status->idAdmin = $jwtPayload->id;
                            $status->idDenuncia = $request->idDenuncia;
                            $status->admin = $admin->name;
                            $status->status = $request->status;
                            $status->org = $admin->org;
                            $status->message = $request->message;
                            $status->save();

                            return response()->json([
                                "message" => "status record created",
                            ], 201);
                        }
                    } else {
                        return response()->json([
                            "message" => "denuncia not found or status already exists",
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
    
    public function getStatus(Request $request, $idDenuncia)
    {
        if(!empty($request->all())) {
            
            $tokenParts = explode(".", $request->token);
            
            $tokenHeader = base64_decode($tokenParts[0]);
            $jwtHeader = json_decode($tokenHeader);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);
            $isAdmin = new AdminController;
            $tokenValidAdmin = $isAdmin->validacaoJwt($request);

            switch($tokenValidAdmin) {

                case 1:
                    if(Status::where('idDenuncia', $idDenuncia)->exists()) {
                        $status = Status::where('idDenuncia', $idDenuncia)->get();
                        return response()->json(
                            $status
                        , 200);
                    } else {
                        return response()->json([
                            "message" => "status not found",
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
    public function edit(Request $request, $idDenuncia)
    {

        if(!empty($request->all())) {

            $tokenParts = explode(".", $request->token);
            
            $tokenHeader = base64_decode($tokenParts[0]);
            $jwtHeader = json_decode($tokenHeader);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtPayload = json_decode($tokenPayload);
            $isAdmin = new AdminController;
            $tokenValidAdmin = $isAdmin->validacaoJwt($request);
                
            switch($tokenValidAdmin) {
                    
                case 1:
                    if(Status::where('idDenuncia', $idDenuncia)->exists()) {
                        $status = Status::where('idDenuncia', $idDenuncia)->get();
                        $status->admin = is_null($request->admin) ? $status->admin : $request->admin;
                        $status->status = is_null($request->status) ? $status->status : $request->status;
                        $status->message = is_null($request->message) ? $status->message : $request->message;
                        $status->update();
                        
                        return response()->json([
                            "message" => "status records updated successfully",
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "status not found",
                        ], 400);
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
        }
    }    
        
}
