<?php
                            namespace App\Http\Controllers\RmaSubClassifications;
                            use App\Helpers\CommonHelpers;
                            use App\Http\Controllers\Controller;
                            use Illuminate\Http\Request;
                            use Illuminate\Http\RedirectResponse;
                            use Illuminate\Support\Facades\Auth;
                            use Illuminate\Support\Facades\Session;
                            use Inertia\Inertia;
                            use Inertia\Response;
                            use DB;

                            class RmaSubClassificationsController extends Controller{
                                public function getIndex(){
                                    return Inertia("RmaSubClassifications/RmaSubClassifications");
                                }
                            }
                        ?>