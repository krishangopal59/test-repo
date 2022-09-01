<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use JWTAuth;

class AuthController extends Controller
{
    
    /**
     * login api 
     * @request email string
     * @request password string 
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {  
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'email' => 'required|string',
                'password' => 'required',      
            ],[
                'password.required_if' => "Password is required for manual login",
            ]);
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = "failed" ;
                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
    
            $credentials = $request->only('email', 'password');
            $token = JWTAuth::attempt($credentials);
        
            if(!empty($token)){
                $response['token'] = $token;
                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "You are logged in successfully!";
            }else{
                $response['code'] = 404;
                $response['status'] = 'failed';
                $response['message'] = @$input['is_social'] == 1 ? "This user is not registered with us. Please signup first." : "You have entered wrong email id or Password";            
            }
        }catch (\Exception $e){
            $response['code'] = 403;
            $response['status'] = "failed" ;
            $response['message'] = "You are not allowed to access this without details.";
            return response()->json($response);
        } 
        return response()->json($response);
        
    }


    /**
     * contact list api 
     *
     * @return \Illuminate\Http\Response
     */
    public function contacts(Request $request){
        try {
            $contacts=Contact::all();
            if($contacts){
                $response['contacts'] = $contacts;
                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Contacts list";
                return response()->json($response);
            }else{
                $response['contacts'] = $contacts;
                $response['code'] = 403;
                $response['status'] = "failed";
                $response['message'] = "Contacts list not found";
                return response()->json($response);
            }
        }catch (\Exception $e){
            $response['code'] = 403;
            $response['status'] = "failed" ;
            $response['message'] = "You are not allowed to access this without contact token.";
            return response()->json($response);
        } 

    }

    /**
     * Add contact  api 
     * @request address string
     * @request mobile string 
     * @headder  Authorization Brarer 'Auth token' string 
     * 
     * @return \Illuminate\Http\Response
     */

    public function add_contacts(Request $request){
        try {
            $user = JWTAuth::parseToken()->authenticate(); 
            $Contact= new Contact();
            $Contact->user_id= $user->id;
            $Contact->address= $request['address'];
            $Contact->mobile= $request['mobile'];
            $Contact->save();
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = "Contact added successfully..";
            return response()->json($response);
        }catch (\Exception $e){
            $response['code'] = 403;
            $response['status'] = "failed" ;
            $response['message'] = "You are not allowed to access this without contact details.";
            return response()->json($response);
        } 

    }

     /**
     * Update contact Api 
     * @request address string
     * @request mobile string 
     * @request id string 
     * @headder  Authorization Brarer 'Auth token' string 
     * 
     * @return \Illuminate\Http\Response
     */

    public function update_contacts(Request $request){
        $user = JWTAuth::parseToken()->authenticate(); 
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'required'
                    
            ]);
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = "failed" ;
                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
            
       
            $Contact = Contact::find($request->id);
            if($Contact){
                // $Contact = Contact::find($request->id);
                $Contact->address= $request['address'];
                $Contact->mobile= $request['mobile'];
                $Contact->save();
                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Contact updated successfully..";
            }else{
                $response['code'] = 404;
                $response['status'] = "failed" ;
                $response['message'] = "You are not allowed to update contacts.";
                return response()->json($response);
            }
        }catch (\Exception $e){
            $response['code'] = 403;
            $response['status'] = "failed" ;
            $response['message'] = "You are not allowed to access this without token.";
            return response()->json($response);
        }  
        return response()->json($response);

    }
    /**
     * Get a individual contact api 
     * @request id string 
     * @headder  Authorization Brarer 'Auth token' string 
     * 
     * @return \Illuminate\Http\Response
     */

    public function get_contact(Request $request){
        try {
            $contacts = JWTAuth::parseToken()->authenticate();
            $contacts=Contact::where('id',$request->id)->first();
            if($contacts){
                $response['contacts'] = $contacts;
                $response['code'] = 200;
                $response['status'] = "success";
                $response['message'] = "Contact details.";
                return response()->json($response);
            }else{
                $response['code'] = 403;
                $response['status'] = "success";
                $response['message'] = "Contact not found.";
                return response()->json($response); 
            }
        }catch (\Exception $e){
            $response['code'] = 403;
            $response['status'] = "failed" ;
            $response['message'] = "You are not allowed to access this without contact id.";
            return response()->json($response);
        } 
    } 

    /**
     * Delete contact api 
     * @request id string 
     * @headder  Authorization Brarer 'Auth token' string 
     * 
     * @return \Illuminate\Http\Response
    */
    public function delete(Request $request){
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user=Contact::where('id',$request->id)->delete();
            if($user){
                $response['code'] = 200;
                $response['status'] = "success";
                $response['message'] = "Contact deleted successfully.";
              return response()->json($response);
            }else{
                $response['code'] = 403;
                $response['status'] = "success";
                $response['message'] = "Contact not found.";
                return response()->json($response);
            }
        }catch (\Exception $e){
            $response['code'] = 403;
            $response['status'] = "failed" ;
            $response['message'] = "You are not allowed to access this without contact id.";
            return response()->json($response);
        } 
    } 

    
   
    
}
