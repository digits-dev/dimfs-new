<?php
                            namespace App\Http\Controllers\Incoterms;
                            use App\Helpers\CommonHelpers;
                            use App\Http\Controllers\Controller;
                            use Illuminate\Http\Request;
                            use Illuminate\Http\RedirectResponse;
                            use Illuminate\Support\Facades\Auth;
                            use Illuminate\Support\Facades\Session;
                            use Inertia\Inertia;
                            use Inertia\Response;
                            use DB;

                            class IncotermsController extends Controller{
                                public function getIndex(){
                                    return Inertia("Incoterms/Incoterms");
                                }
                            }
                        ?>