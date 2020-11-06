<?php

namespace App\Controllers;

use App\Libraries\Crud;


class Vulnerabilities extends BaseController
{
	protected $crud;
	//  		$base = null, //prefix uri or parrent controller.
	//         $action = 'add',  //determine create or update // default is create (add)
	//         $table, //string
	//         $table_title, //string
	//         $form_title_add, //string
	//         $form_title_update, //string
	//         $form_submit, //string
	//         $form_submit_update, //string
	//         $fields = [], //array of field options: (type, required, label),
	//         $id,  //primary key value
	//         $id_field,  //primary key field
	//         $current_values, //will get current form values before updating
	//         $db, //db connection instance
	//         $model, //db connection instance
	//         $request;
	function __construct()
	{
		$params = [
			'table' => 'vulnerabilities',
			'dev' => false,
			'fields' => $this->field_options(),
			'form_title_add' => 'Add Risk Log',
			'form_title_update' => 'Edit Risk Log',
			'form_submit' => 'Add',
			'table_title' => 'Risk Logs',
			'form_submit_update' => 'Update',
			'base' => '',

		];

		$this->crud = new Crud($params, service('request'));
	}

	public function index()
	{

		$page = 1;
		if (isset($_GET['page'])) {
			$page = (int) $_GET['page'];
			$page = max(1, $page);
		}

		$data['title'] = $this->crud->getTableTitle();

		$per_page = 10;
		$columns = ['status','risk_impact','probability_of_occurrence',
			'risk_map','project_impact','risk_area',
			'risk_response_strategy','response_strategy',
			'date_time','project_name','scan_type'];
		$where = null;
		$order = [
			['id', 'ASC']
		];
		$data['table'] = $this->crud->view($page, $per_page, $columns, $where, $order);
		return view('admin/risks/table', $data);
	}

	function add()
	{
		$data['form'] = $form = $this->crud->form();
		$data['title'] = $this->crud->getAddTitle();
		
		if(is_array($form) && isset($form['redirect']))
			return redirect()->to($form['redirect']);
		
		return view('admin/risks/form', $data);
	}

	function edit($id)
	{
		if(!$this->crud->current_values($id))
			return redirect()->to($this->crud->getBase().'/'.$this->crud->getTable());

		$data['item_id'] = $id;
		$data['form'] = $form = $this->crud->form();

		if (is_array($form) && isset($form['redirect']))
			return redirect()->to($form['redirect']);

		$data['title'] = $this->crud->getEditTitle();
		return view('admin/risks/form', $data);
		
	}

	protected function field_options()
	{
		$fields = [];
		$fields['id'] = ['label' => 'ID'];
		$fields['status'] = ['label' => 'Status', 'required' => true];
		$fields['risk_impact'] = ['label' =>'Risk Impact'];
		$fields['risk_description'] = ['label' =>'Risk Description'];
		$fields['probability_of_occurrence'] = ['label' =>'Occurrence'];
		$fields['risk_map'] = ['label' => 'Risk Map','required' => true];
		$fields['project_impact'] = ['label' =>'Project Impact'];
		$fields['risk_area'] = ['label' =>'Risk Area'];
		$fields['symptoms'] = ['label' =>'Symptoms'];
		$fields['triggers'] = ['label' =>'Trigger'];
		$fields['risk_response_strategy'] = ['label' =>'Risk Response Strategy'];
		$fields['response_strategy'] = ['label' =>'Response Strategy'];
		$fields['contingency_plan'] = ['label' =>'Contingency Plan'];
		$fields['u_created_at'] = ['label' => 'Created at','only_edit' => true];
		$fields['audit_notes_guidance'] = ['label' =>'Audit Notes'];
		$fields['project_name'] = ['label' =>'Project'];
		$fields['scan_type'] = ['label' =>'Scan Source'];

		return $fields;
	}

	//--------------------------------------------------------------------

}
