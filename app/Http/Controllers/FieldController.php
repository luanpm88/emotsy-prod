<?php

namespace Mailixa\Http\Controllers;

use Illuminate\Http\Request;

use Mailixa\Http\Requests;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        $fields = $list->getFields;
        
        // Get old post values
        if(isset($request->old()["fields"])) {
            $fields = collect();
            foreach($request->old()["fields"] as $key => $item) {
                $field = \Mailixa\Model\Field::findByUid($item["uid"]);
                if (!is_object($field)) {
                    $field = new \Mailixa\Model\Field();
                    $field->uid = $key;
                }
                $field->fill($item);
                
                // Field options
                if(isset($item["options"])) {
                    $field->fieldOptions = collect();
                    foreach($item["options"] as $key2 => $item2) {
                        $option = new \Mailixa\Model\FieldOption($item2);
                        $option->uid = $key2;
                        $field->fieldOptions->push($option);
                    }
                }
                $fields[] = $field;
            }
        }
        
        return view('fields.index', [
            'list' => $list,
            'fields' => $fields,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        
        // validate and save posted data
        if ($request->isMethod('post'))
        {
            $rules = [];
            
            // Check if filed does not have EMAIL tag
            $has_main_tag = false;
            $conflict_tag = false;
            $tags = [];
            foreach($request->fields as $key => $item) {
                if ($item["tag"] == 'EMAIL') {
                    $has_main_tag = true;
                }
                
                // check required input
                $rules["fields." . $key . ".label"] = 'required';
                $rules["fields." . $key . ".tag"] = 'required|alpha_dash';
                
                // check field options
                if(isset($item["options"])) {
                    foreach($item["options"] as $key2 => $item2) {
                        $rules["fields." . $key . ".options." . $key2 . ".label"] = 'required';
                        $rules["fields." . $key . ".options." . $key2 . ".value"] = 'required';
                    }
                }
                
                // Check tag exsit
                $tag = \Mailixa\Model\Field::formatTag($item["tag"]);
                if (in_array($tag, $tags)) {
                    $conflict_tag = true;
                }
                $tags[] = $tag;
            }
            if (!$has_main_tag) {
                $rules["miss_main_field_tag"] = 'required';
            }
            if ($conflict_tag) {
                $rules["conflict_field_tags"] = 'required';
            }
            $this->validate($request, $rules);
            
            // Save fields
            $saved_ids = [];
            foreach($request->fields as $uid => $item) {
                $field = \Mailixa\Model\Field::findByUid($item["uid"]);
                if (!is_object($field)) {
                    $field = new \Mailixa\Model\Field();
                    $field->mail_list_id = $list->id;
                }
                
                // save exsit field
                $item["tag"] = \Mailixa\Model\Field::formatTag($item["tag"]);
                $field->fill($item);
                $field->save();
                
                // save field options
                $field->fieldOptions()->delete();
                if(isset($item["options"])) {
                    foreach($item["options"] as $key2 => $item2) {
                        $option = new \Mailixa\Model\FieldOption($item2);
                        $option->field_id = $field->id;
                        $option->save();
                    }
                }
                
                // store save ids
                $saved_ids[] = $field->uid;
            }
            // Delete fields
            foreach($list->getFields as $field) {
                if(!in_array($field->uid, $saved_ids)) {
                    $field->delete();
                }
            }
            
            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.fields.updated'));
            return redirect()->action('FieldController@index', $list->uid);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Custom sort items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $sort = json_decode($request->sort);
        foreach($sort as $row) {
            $list = \Mailixa\Model\Field::findByUid($row[0]);
            $list->custom_order = $row[1];
            $list->save();            
        }
        
        echo trans('messages.fields.custom_order.updated');
    }
    
    /**
     * Get field sample.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sample(Request $request)
    {
        $list = \Mailixa\Model\MailList::findByUid($request->list_uid);
        
        return view('fields._form_samples', [
            'list' => $list,
            'type' => $request->type,
        ]);
    }
    
    /**
     * Delete an item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $field = \Mailixa\Model\Field::findByUid($request->uid);
        
        $field->delete();
        
        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.fields.deleted'));
        return redirect()->action('FieldController@index', $request->list_uid);
    }
}
