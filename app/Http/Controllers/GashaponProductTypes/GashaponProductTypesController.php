<?php
                            namespace App\Http\Controllers\GashaponProductTypes;
                            use App\Helpers\CommonHelpers;
                            use App\Http\Controllers\Controller;
                            use Illuminate\Http\Request;
                            use Illuminate\Http\RedirectResponse;
                            use Illuminate\Support\Facades\Auth;
                            use Illuminate\Support\Facades\Session;
                            use Inertia\Inertia;
                            use Inertia\Response;
                            use DB;

                            class GashaponProductTypesController extends Controller{
                                public function getIndex(){
                                    return Inertia("GashaponProductTypes/GashaponProductTypes");
                                }
                            }
                        ?>