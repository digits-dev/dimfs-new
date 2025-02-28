<?php
                            namespace App\Http\Controllers\RmaItemMasterHistories;
                            use App\Helpers\CommonHelpers;
                            use App\Http\Controllers\Controller;
                            use Illuminate\Http\Request;
                            use Illuminate\Http\RedirectResponse;
                            use Illuminate\Support\Facades\Auth;
                            use Illuminate\Support\Facades\Session;
                            use Inertia\Inertia;
                            use Inertia\Response;
                            use DB;

                            class RmaItemMasterHistoriesController extends Controller{
                                public function getIndex(){
                                    return Inertia("RmaItemMasterHistories/RmaItemMasterHistories");
                                }
                            }
                        ?>