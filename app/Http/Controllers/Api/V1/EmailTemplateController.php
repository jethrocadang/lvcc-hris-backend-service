<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTemplateRequest;
use App\Http\Resources\EmailTemplateResource;
use Illuminate\Validation\Rules\Email;

class EmailTemplateController extends Controller
{
    public function createEmail(EmailTemplateRequest $request){

        //create email
        $email = EmailTemplate::create($request->validated());
        
        return response()->json([
            'message' => 'Email template created successfully!',
            'email' => new EmailTemplateResource($email)
        ], 201);
    }

    public function getEmails(){

        //get all email templates
        $email = EmailTemplate::all();

        return response()->json([
            'email_templates' => EmailTemplateResource::collection($email)
        ], 200);
    }

    public function updateEmail(EmailTemplateRequest $request, $id){

        // update email template by id
        $email = EmailTemplate::findOrFail($id);

        $email->update($request->validated());

        return response()->json([
            'message' => 'Email template updated successsfuly!',
            'email' => new EmailTemplateResource($email)
        ], 200);
    }

    public function deleteEmail($id){

        //delete email template by id
        $email = EmailTemplate::findOrFail($id);

        $email->delete();

        return response()->json([
            'message' => 'Email template deleted successfully!'
        ], 200);
    }
}
