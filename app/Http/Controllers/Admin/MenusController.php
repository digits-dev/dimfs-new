<?php

namespace App\Http\Controllers\Admin; 
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdmModels\AdmMenus;
use App\Models\AdmModels\admMenusPrivileges;
use App\Models\AdmModels\AdmPrivileges;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class MenusController extends Controller{
  

    public function getIndex(){
   
        if (!CommonHelpers::isView()) {
            CommonHelpers::redirect(CommonHelpers::adminPath(), 'Denied Access');
        }

        $data = [];
        $data['tableName'] = 'adm_users';
        $data['page_title'] = 'Users Management';
        $data['privileges'] = AdmPrivileges::select('id as value', 'name as label')->get();

        $data['menus'] = AdmMenus::with([
            'children' => function ($query)  {
                $query->with('getMenusPrivileges.getPrivilege')->where('is_active', 1)
                ->orderBy('sorting');
            }, 'getMenusPrivileges.getPrivilege'
        ])
        ->where('parent_id', 0)
        ->where('is_active', 1)
        ->orderBy('sorting')
        ->get();

        return Inertia::render('AdmVram/MenuManagement/MenuManagement', $data);
    }

    public function createMenu(Request $request)
    {
        $validatedFields = $request->validate([
            'privilege_name' => 'required',
            'menu_name' => 'required',
            'menu_type' => 'required',
            'menu_icon' => 'required',
            'path' => 'required',
            'slug' => 'required_if:menu_type,Route',
            'id_adm_privileges' => 'required|integer'
        ]);

        try {

            DB::beginTransaction();
    
            $menu = AdmMenus::create([
                'name' => $validatedFields['menu_name'], 
                'type' => $validatedFields['menu_type'],   
                'path' => $validatedFields['path'], 
                'slug' => $request->slug ?? null, 
                'icon' => $validatedFields['menu_icon'], 
                'parent_id' => 0, 
                'is_active' => 1, 
                'id_dashboard' => 0, 
                'id_adm_privileges' => 1, 
                'sorting' => 0, 
            ]);

            admMenusPrivileges::create([
                'id_adm_menus' => $menu->id, 
                'id_adm_privileges' => $request->id_adm_privileges,   
            ]);

            DB::commit();

            
            $menus = AdmMenus::with([
                'children' => function ($query)  {
                    $query->with('getMenusPrivileges.getPrivilege')->where('is_active', 1)
                    ->orderBy('sorting');
                }, 'getMenusPrivileges.getPrivilege'
                ])
                ->where('parent_id', 0)
                ->where('is_active', 1)
                ->orderBy('sorting')
                ->get();

            return back()->with(['message' => 'Menu Creation Success!', 'type' => 'success', 'menus' => $menus ]);
        }  

        catch (\Exception $e) {

            DB::rollBack();
            CommonHelpers::LogSystemError('Menu Management', $e->getMessage());
            return back()->with(['message' => 'Menu Creation Failed!', 'type' => 'error']);
        }
        
    }


    public function autoUpdateMenu(Request $request) {

        $sorting = 1;

        foreach ($request->items as $item) {
            $menu = AdmMenus::find($item['id']);
            $menu->sorting = $sorting;
            $menu->parent_id = 0;

            $child_sorting = 1;

            if (isset($item['children']) && !empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    $child_menu = AdmMenus::find($child['id']);
                    $child_menu->sorting = $child_sorting;
                    $child_menu->parent_id = $item['id'];

                    $child_menu->save();
                    $child_sorting++;
                }
            }

            $menu->save();
            $sorting++;
        }

        return json_encode(["message"=> 'Menu Updated', "type"=>"success"]);

      
    }
    
    public function editMenu($menu){
        if (!CommonHelpers::isView()) {
            CommonHelpers::redirect(CommonHelpers::adminPath(), 'Denied Access');
        }
        
        $data = [];
        $data['privileges'] = AdmPrivileges::select('id as value', 'name as label')->get();
        $data['tableName'] = 'adm_users';
        $data['page_title'] = 'Users Management - Edit';
        $data['menu'] = AdmMenus::with([
            'children' => function ($query)  {
                $query->with('getMenusPrivileges.getPrivilege')->where('is_active', 1)
                ->orderBy('sorting');
            }, 'getMenusPrivileges.getPrivilege'
        ])
        ->find($menu);

        return Inertia::render('AdmVram/MenuManagement/MenuManagementEdit', $data);
    }
    

  
}

?>