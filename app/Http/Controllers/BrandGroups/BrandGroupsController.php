<?php
                            namespace App\Http\Controllers\BrandGroups;
                            use App\Helpers\CommonHelpers;
                            use App\Http\Controllers\Controller;
                            use Illuminate\Http\Request;
                            use Illuminate\Http\RedirectResponse;
                            use Illuminate\Support\Facades\Auth;
                            use Illuminate\Support\Facades\Session;
                            use Inertia\Inertia;
                            use Inertia\Response;
                            use DB;

                            class BrandGroupsController extends Controller{
                                public function getIndex(){
                                    return Inertia("BrandGroups/BrandGroups");
                                }
                            }
                        ?>