<?php
class EmailerApi 
{
    private $email;
	private $password;
	
	private function curlRequestResponse($fields)
	{
		$Curl_Session = curl_init();
		curl_setopt($Curl_Session,CURLOPT_URL,'https://apps.webmation.ws/xml.pl');
		//curl_setopt($Curl_Session,CURLOPT_URL,'https://apps.webmation.ws/xml.pl');
		curl_setopt($Curl_Session, CURLOPT_POST, 1);
		curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($Curl_Session);
		$header = curl_getinfo($Curl_Session);
		if(curl_errno($Curl_Session))
		{
		   return 'Curl error: ' . curl_error($Curl_Session);
		}
		curl_close($Curl_Session);
		return $content;
	}
	
	public function apiResponse($email_arg,$password_arg)
	{
		$this->email    = trim($email_arg);
		$this->password = trim($password_arg);
		
		$fields = array('email'=>$this->email,
				        'password'=>$this->password,
				        'xml'=>'<GetAuthTokenRequest></GetAuthTokenRequest>'
         );
		$Curl_Session = curl_init();
		curl_setopt($Curl_Session,CURLOPT_URL,'https://apps.webmation.ws/xml.pl');
		//curl_setopt($Curl_Session,CURLOPT_URL,'https://apps.webmation.ws/xml.pl');
		curl_setopt($Curl_Session, CURLOPT_POST, 1);
		curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($Curl_Session);
		$header = curl_getinfo($Curl_Session);
		if(curl_errno($Curl_Session))
		{
		   return 'Curl error: ' . curl_error($Curl_Session);
		}
		curl_close($Curl_Session);
		$xml2 = simplexml_load_string($content);
		//print_r($xml2);
		$arr['Result']    = $xml2->Result;
		$arr['Token']     = $xml2->Token;
		$arr['ErrorText'] = $xml2->ErrorText;
		
		return $arr;
	}
	
	public function createGroup($email_arg,$auth_token,$group_name)
	{
		$fields = array('email'=>trim($email_arg),
				        'auth_token'=>trim($auth_token),
				        'xml'=>'<CreateGroupRequest><Name>'.trim($group_name).'</Name></CreateGroupRequest>'
         				);
		$content = $this->curlRequestResponse($fields);
		$xml3 = simplexml_load_string($content);
		$arr_group['Result']       = $xml3->Result;
		$arr_group['Group_id']     = $xml3->Group_id;
		$arr_group['ErrorText']    = $xml3->ErrorText;
		return $arr_group;
	}
	
	public function deleteGroup($email_arg,$auth_token,$group_id)
	{
		$fields = array('email'=>trim($email_arg),
				        'auth_token'=>trim($auth_token),
				        'xml'=>'<DeleteGroupsRequest><Groups><Group group_id="'.trim($group_id).'" /></Groups></DeleteGroupsRequest>'
         				);
		
		$content = $this->curlRequestResponse($fields);
		$xmlg = simplexml_load_string($content);
		$arr_group['Result']       = $xmlg->Groups->Group->Result;
		$arr_group['Group_id']     = $xmlg->Groups->Group->Group_id;
		$arr_group['ErrorText']    = $xmlg->Groups->Group->ErrorText;
		return $arr_group;
		
	}
	
	public function getGroupInfo($email_arg,$auth_token,$group_name)
	{
		$fields = array('email'=>trim($email_arg),
				        'auth_token'=>trim($auth_token),
				        'xml'=>'<GetGroupsRequest group_name="'.trim($group_name).'"></GetGroupsRequest>'
         				);
		
		$content = $this->curlRequestResponse($fields);
		$xmlg = simplexml_load_string($content);
		$arr_group['Result']       = $xmlg->Result;
		$arr_group['Group_id']     = $xmlg->Groups->Group_id;
		$arr_group['Name']     	   = $xmlg->Groups->Name;
		$arr_group['Group_type']   = $xmlg->Groups->Group_type;
		
		return $arr_group;
	}
	
	public function addContact($api_email,$auth_token,$group_name,$contact_email,$contact_fname,$contact_lname)
	{
		$fields = array('email'=>trim($api_email),
				        'auth_token'=>trim($auth_token),
				        'xml'=>'<AddContactsRequest>
									<Contacts>
									  <Contact>
											<Firstname>'.$contact_fname.'</Firstname>
											<Lastname>'.$contact_lname.'</Lastname>
											<Email>'.$contact_email.'</Email>
											<Groups>
											  <Group>'.$group_name.'</Group>
											</Groups>
									  </Contact>
									</Contacts>
								</AddContactsRequest>'
         );
		$content = $this->curlRequestResponse($fields);
		$xml4 = simplexml_load_string($content);
		$arr_contact['Result']         = $xml4->Contacts->Contact->Result;
		$arr_contact['Contact_id']     = $xml4->Contacts->Contact->Contact_id;
		$arr_contact['ErrorText']      = $xml4->Contacts->Contact->ErrorText;
		return $arr_contact;
		//return $xml4->Contacts->Contact->Contact_id;
	}

	/*
	** @param,  $group_name should be an array
	*/
	public function addContactToMultipleGroup($api_email,$auth_token,$group_names,$contact_email,$contact_fname,$contact_lname)
	{
		if(is_array($group_names) && count($group_names)> 0)
		{
			$group_str = '';
			foreach($group_names as $gname){
				$group_str .='<Group>'.$gname.'</Group>';
			}
			
			$fields = array('email'=>trim($api_email),
							'auth_token'=>trim($auth_token),
							'xml'=>'<AddContactsRequest>
										<Contacts>
										  <Contact>
												<Firstname>'.$contact_fname.'</Firstname>
												<Lastname>'.$contact_lname.'</Lastname>
												<Email>'.$contact_email.'</Email>
												<Groups>'.$group_str.'</Groups>
										  </Contact>
										</Contacts>
									</AddContactsRequest>'
			 );
			$content = $this->curlRequestResponse($fields);
			$xml4 = simplexml_load_string($content);
			
			$arr_contact['Result']         = $xml4->Contacts->Contact->Result;
			$arr_contact['Contact_id']     = $xml4->Contacts->Contact->Contact_id;
			$arr_contact['ErrorText']      = $xml4->Contacts->Contact->ErrorText;
			$arr_contact['ErrorCode']      = $xml4->Contacts->Contact->ErrorCode;
			return $arr_contact;
			//return $xml4;
			
		}
	}
	
	public function deleteContact($api_email,$auth_token,$contact_id)
	{
		$fields = array('email'=>trim($api_email),
				        'auth_token'=>trim($auth_token),
				        'xml'=>'<DeleteContactsRequest>
								 <Contacts>
								   <Contact contact_id="'.$contact_id.'" />
								 </Contacts>
								</DeleteContactsRequest>'
         );
		$content = $this->curlRequestResponse($fields);
		$xml5 = simplexml_load_string($content);
		$arr_contact_del['Result']         = $xml5->Contacts->Contact->Result;
		$arr_contact_del['ErrorText']      = $xml5->Contacts->Contact->ErrorText;
		return $arr_contact_del;
	}
	
	public function getContactID($api_email,$auth_token,$user_email)
	{
		$fields = array('email'=>trim($api_email),
						'auth_token'=>trim($auth_token),
						'xml'=>'<GetContactsRequest email="'.trim($user_email).'">
								</GetContactsRequest>');
		
		$content = $this->curlRequestResponse($fields);
		
		$xmlresp = simplexml_load_string($content);
		
		$arr_contact['Result']					= $xmlresp->Result;		
		$arr_contact['TotalMatchingContacts'] 	= $xmlresp->TotalMatchingContacts;
		$arr_contact['Contact_id'] 				= $xmlresp->Contacts->Contact->Contact_id;
		//return $xmlresp;
		return $arr_contact;
	}
	
	public function addConatctToGroup($email,$token,$contact_email,$group_id)
	{
		$fields = array('email'=>$email,
						'auth_token'=>trim($token),
						'xml'=>'<AddContactsToGroupRequest group_id="'.intval($group_id).'">
								  <Contacts>
									<Contact email="'.trim($contact_email).'"/>
								  </Contacts>
								</AddContactsToGroupRequest>'
						);
		$content = $this->curlRequestResponse($fields);
		$xml6 = simplexml_load_string($content);
		$arr_contact['Result']    = $xml6->Contacts->Contact->Result;
		$arr_contact['ErrorText'] = $xml6->Contacts->Contact->ErrorText;
		return $arr_contact;
		//return $xml6;
	}
	
	public function deleteContactFromGroup($api_email,$auth_token,$group_id,$contact_id)
	{
		$fields = array('email'=>trim($api_email),
				        'auth_token'=>trim($auth_token),
				        'xml'=>'<DeleteContactsFromGroupRequest group_id="'.$group_id.'">
								 <Contacts>
								   <Contact contact_id="'.$contact_id.'" />
								 </Contacts>
								</DeleteContactsFromGroupRequest>'
						);
		$content = $this->curlRequestResponse($fields);
		$xml5 = simplexml_load_string($content);
		$arr_contact_del['Result']         = $xml5->Contacts->Contact->Result;
		$arr_contact_del['Contact_id']     = $xml5->Contacts->Contact->Contact_id;
		$arr_contact_del['ErrorText']      = $xml5->Contacts->Contact->ErrorText;
		return $arr_contact_del;
		//return $xml5;
	}
	
}