<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function list(){
        $contacts = Contact::query();
        return ContactCollection::make($contacts->paginate(5));
    }

    public function add(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'required',
            'email'=> 'required|email',
            'address'=> 'required',
            'avatar'=> 'required|image',
            'numbers'=> 'required|array',
        ]);

        if($validator->fails()):
            return $validator->errors();
        endif;

        $contact = new Contact();
        $contact->user_id = $request->user()->id;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->address = $request->address;
        $contact->avatar = Storage::putFile('',$request->avatar);

        if($contact->save()):
            $contact->refresh();
            foreach($request->get('numbers') as $n):
                $number = new Number();
                $number->user_id = $request->user()->id;
                $number->contact_id = $contact->id;
                $number->number = $n;
                $number->save();
            endforeach;
            return ContactResource::make($contact);
        endif;

        Storage::delete($contact->avatar);
        return response(400)->json(['message'=>'Can\'t Add Contact Now, Please Try Again Later.']);
    }

    public function edit(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'name'=> 'required',
            'email'=> 'required|email',
            'address'=> 'required',
            'avatar'=> 'required|image',
        ]);

        if($validator->fails()):
            return $validator->errors();
        endif;

        $contact = Contact::find($id);
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->address = $request->address;
        $contact->avatar = Storage::putFile('',$request->avatar);

        if($contact->save()):
            $contact->refresh();
            return ContactResource::make($contact);
        endif;

        return response(400)->json(['message'=>'Can\'t Saved Contact Now, Please Try Again Later.']);
    }

    public function delete($id){
        $contact = Contact::find($id);

        Storage::delete($contact->avatar);

        $contact->numbers()->delete();

        if($contact->delete()):
            return response()->json(['message'=>'Contact Deleted Successfully.']);
        endif;

        return response(400)->json(['message'=>'Can\'t Delete Contact Now. Please Try Again Later.']);
    }
}
