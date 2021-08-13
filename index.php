<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	private static $mainUrl 		= 'https://sandbox.partner.api.bri.co.id';
	private static $costumerKey 	= '';
	private static $costumerSecret	= '';
	private static $accessToken		= null;
	private static $timeStamp		= null;
	private static $signature		= null;


	public function __construct()
	{
		parent::__construct();
		$this->guzzle		= new \GuzzleHttp\Client;
		$date = new DateTime('UTC');
		self::$timeStamp	= $date->format('o-m-d\TH:i:s.') . substr($date->format('u'),0,3) . 'Z';
		$this->_getToken();
	}


	public function index()
	{

		// echo json_encode(self::$timeStamp);
		// echo "<br>";
		// echo json_encode(self::$accessToken);
		// echo "<br>";
		

		// print ('&times');die;
		// print self::$signature = $this->_getSignature($method, $url);die;
		// var_dump($this->_getSignature($method, $url));
	

		// $this->load->view('welcome_message');
		// echo self::$accessToken;
		// echo "</br>";

		// echo 

		// echo json_encode($this->_getBalance());

		// $body = [
		// 	'accountNumber'	=> '008301031142500',
		// 	'startDate'		=> '2020-12-01',
		// 	'endDate'		=> '2020-12-30'
		// ];

		// echo json_encode($this->_getStatement($body));

		// $body = [
		// 	'sourceAccount'			=> '020601000255504',
		// 	'beneficiaryAccount'	=> '020601006031306'
		// ];

		// echo json_encode($this->_getAccountValidationInternal($body));


		// $body = [
		// 	'noReferral'			=> '20210202126111111333',
		// 	'sourceAccount'			=> '020601000255504',
		// 	'beneficiaryAccount'	=> '020601006031306',
		// 	'amount'				=> '10000.00',
		// 	'feeType'				=> 'OUR',
		// 	'transactionDateTime'	=> '13-08-2021 11:32:59',
		// 	'remark'				=> 'REMARK TEST 20210202126111111333',
		// ];

		// echo json_encode($this->_postTransferInternal($body));


		// $body = [
		// 	'noReferral'		=> 'ABCD2021033112369',
		// 	'transactionDate'	=> '01-04-2021'
		// ];

		// echo json_encode($this->_getRekKoran($body));

		// echo json_encode($this->_getListBankCode());

		// $bankCode = '014';
		// $beneficiaryAccount = '8888123123';

		// echo json_encode($this->_getAccountValidationExternal($bankCode, $beneficiaryAccount));


	// 	$body = [
	// 		'noReferral'				=> '33333333333',
	// 		'bankCode'					=> '014',
	// 		'sourceAccount'				=> '888801000003301',
	// 		'beneficiaryAccount'		=> '12345678',
	// 		'beneficiaryAccountName'	=> 'DUMMY NAME PRM                ',
	// 		'amount'					=> '1000.00'
	// 	];

	// 	echo json_encode($this->_postTransferExternal($body));


	// $body = [
	// 	'institutionCode'	=> 'J104408',
	// 	'brivaNo'			=> '77777',
	// 	'custCode'			=> '9980994091',
	// 	'nama'				=> 'User 11',
	// 	'amount'			=> '1000000',
	// 	'keterangan'		=> 'Pembayaran UKT Genap 2021/2022',
	// 	'expiredDate'		=> '2021-08-14 23:59:59'
	// ];


	// echo json_encode($this->_postCreateVa($body));


	
	// $institutionCode	= 'J104408';
	// $brivaNo			= '77777';
	// $custCode			= '9980994090';
	
	// echo json_encode($this->_getVa($institutionCode, $brivaNo, $custCode));


	// $institutionCode	= 'J104408';
	// $brivaNo			= '77777';
	// $custCode			= '9980994091';
	
	// echo json_encode($this->_getStatusVa($institutionCode, $brivaNo, $custCode));

	// $body = [
	// 	'institutionCode'	=> 'J104408',
	// 	'brivaNo'			=> '77777',
	// 	'custCode'			=> '9980994091',
	// 	'statusBayar'		=> 'Y'
	// ];

	// echo json_encode($this->_putUpdateStatusVa($body));

	// $institutionCode	= 'J104408';
	// $brivaNo			= '77777';
	// $custCode			= '9980994091';

	// echo json_encode($this->_delVa($institutionCode, $brivaNo, $custCode));

	// $institutionCode	= 'J104408';
	// $brivaNo			= '77777';
	// $startDate			= '20210629';
	// $endDate			= '20210629';

	// echo json_encode($this->_getReportVa($institutionCode, $brivaNo, $startDate, $endDate));

	$institutionCode	= 'J104408';
	$brivaNo			= '77777';
	$startDate			= '2021-06-14';
	$startTime			= '05:00';
	$endDate			= '2021-06-14';
	$endTime			= '20:00';

	echo json_encode($this->_getReportTimeVa($institutionCode, $brivaNo, $startDate, $startTime, $endDate, $endTime));


	}





	private function _getToken(){
		$url = '/oauth/client_credential/accesstoken';
		$output = $this->guzzle->request('POST', self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'Content-Type'	=> 'application/x-www-form-urlencoded'
			],
			'form_params'	=> [
				'client_id'		=> self::$costumerKey,
				'client_secret'	=> self::$costumerSecret
			],
			'query'	=> [
				'grant_type'	=> 'client_credentials'
			]
		]);

		$output = json_decode($output->getBody()->getContents());

		return self::$accessToken = $output->access_token;
	}


	private function _getSignature($method, $url, $body = ''){

		$payload = 'path=' . $url . '&' . 'verb=' . $method . '&' . 'token=Bearer ' . self::$accessToken . '&timestamp=' . self::$timeStamp . '&' . 'body=' . $body;

		$hash = hash_hmac('sha256', $payload, self::$costumerSecret, true);
    	$signature = base64_encode($hash);

		return $signature;
	}


	public function _getBalance(){

		$method = 'GET';
		$accountNumber = '888801000157508';
		$url = '/v2/inquiry/' . $accountNumber;

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'	=> self::$timeStamp,
				'BRI-Signature'	=> $this->_getSignature($method, $url),
				'Authorization'	=> 'Bearer ' . self::$accessToken
			]
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _getStatement($body){

		$method = 'POST';
		$accountNumber = '888801000157508';
		$url = '/v2.0/statement';

		$body = json_encode($body);

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'		=> self::$timeStamp,
				'BRI-Signature'		=> $this->_getSignature($method, $url, $body),
				'Content-Type'		=> 'application/json',
				'BRI-External-Id'	=> '1234',
				'Authorization'		=> 'Bearer ' . self::$accessToken
			],
			'body'		=> $body
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _getAccountValidationInternal($body){

		$method = 'POST';
		$accountNumber = '888801000157508';
		$url = '/v3.1/transfer/internal/accounts';

		$body = json_encode($body);

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'		=> self::$timeStamp,
				'BRI-Signature'		=> $this->_getSignature($method, $url, $body),
				'Content-Type'		=> 'application/json',
				'Authorization'		=> 'Bearer ' . self::$accessToken
			],
			'body'		=> $body
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _postTransferInternal($body){

		$method = 'POST';
		$accountNumber = '888801000157508';
		$url = '/v3.1/transfer/internal';

		$body = json_encode($body);

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'		=> self::$timeStamp,
				'BRI-Signature'		=> $this->_getSignature($method, $url, $body),
				'Content-Type'		=> 'application/json',
				'Authorization'		=> 'Bearer ' . self::$accessToken
			],
			'body'		=> $body
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _getRekKoran($body){

		$method = 'POST';
		$accountNumber = '888801000157508';
		$url = '/v3.1/transfer/internal/check-rekening';

		$body = json_encode($body);

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'		=> self::$timeStamp,
				'BRI-Signature'		=> $this->_getSignature($method, $url, $body),
				'Content-Type'		=> 'application/json',
				'Authorization'		=> 'Bearer ' . self::$accessToken
			],
			'body'		=> $body
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _getListBankCode(){

		$method = 'GET';
		$url = '/v2/transfer/external/accounts';

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'	=> self::$timeStamp,
				'BRI-Signature'	=> $this->_getSignature($method, $url),
				'Authorization'	=> 'Bearer ' . self::$accessToken
			]
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}

	
	public function _getAccountValidationExternal($bankCode, $beneficiaryAccount){

		$method = 'GET';
		$url = '/v2/transfer/external/accounts';

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'	=> self::$timeStamp,
				'BRI-Signature'	=> $this->_getSignature($method, $url),
				'Authorization'	=> 'Bearer ' . self::$accessToken
			],
			'query'		=> [
				'bankcode'				=> $bankCode, 
				'beneficiaryaccount'	=> $beneficiaryAccount
			]
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _postTransferExternal($body){

		$method = 'POST';
		$accountNumber = '888801000157508';
		$url = '/v2/transfer/external';

		$body = json_encode($body);

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'		=> self::$timeStamp,
				'BRI-Signature'		=> $this->_getSignature($method, $url, $body),
				'Content-Type'		=> 'application/json',
				'Authorization'		=> 'Bearer ' . self::$accessToken
			],
			'body'		=> $body
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _postCreateVa($body){

		$method = 'POST';
		$url = '/v1/briva';

		$body = json_encode($body);

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'		=> self::$timeStamp,
				'BRI-Signature'		=> $this->_getSignature($method, $url, $body),
				'Content-Type'		=> 'application/json',
				'Authorization'		=> 'Bearer ' . self::$accessToken
			],
			'body'		=> $body
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _getVa($institutionCode, $brivaNo, $custCode){

		$method = 'GET';
		$url = '/v1/briva/'. $institutionCode .'/' . $brivaNo . '/' . $custCode;

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'	=> self::$timeStamp,
				'BRI-Signature'	=> $this->_getSignature($method, $url),
				'Authorization'	=> 'Bearer ' . self::$accessToken
			]
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _getStatusVa($institutionCode, $brivaNo, $custCode){

		$method = 'GET';
		$url = '/v1/briva/status/'. $institutionCode .'/' . $brivaNo . '/' . $custCode;

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'	=> self::$timeStamp,
				'BRI-Signature'	=> $this->_getSignature($method, $url),
				'Authorization'	=> 'Bearer ' . self::$accessToken
			]
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _putUpdateStatusVa($body){

		$method = 'PUT';
		$url = '/v1/briva/status';

		$body = json_encode($body);

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'		=> self::$timeStamp,
				'BRI-Signature'		=> $this->_getSignature($method, $url, $body),
				'Content-Type'		=> 'application/json',
				'Authorization'		=> 'Bearer ' . self::$accessToken
			],
			'body'		=> $body
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _delVa($institutionCode, $brivaNo, $custCode){

		$method = 'DELETE';
		$url = '/v1/briva';

		$body = 'institutionCode=' . $institutionCode . '&brivaNo=' . $brivaNo . '&custCode=' . $custCode;

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'		=> self::$timeStamp,
				'BRI-Signature'		=> $this->_getSignature($method, $url, $body),
				'Content-Type'		=> 'text/plain',
				'Authorization'		=> 'Bearer ' . self::$accessToken
			],
			'body'		=> $body
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _getReportVa($institutionCode, $brivaNo, $startDate, $endDate){

		$method = 'GET';
		$url = '/v1/briva/report/' . $institutionCode . '/' . $brivaNo . '/' . $startDate . '/' . $endDate;

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'	=> self::$timeStamp,
				'BRI-Signature'	=> $this->_getSignature($method, $url),
				'Authorization'	=> 'Bearer ' . self::$accessToken
			]
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}


	public function _getReportTimeVa($institutionCode, $brivaNo, $startDate, $startTime, $endDate, $endTime){

		$method = 'GET';
		$url = '/v1/briva/report_time/' . $institutionCode . '/' . $brivaNo . '/' . $startDate . '/' . $startTime . '/' . $endDate . '/' . $endTime;

		$output = $this->guzzle->request($method, self::$mainUrl . $url, [
			'verify'	=> false,
			'headers'	=> [
				'BRI-Timestamp'	=> self::$timeStamp,
				'BRI-Signature'	=> $this->_getSignature($method, $url),
				'Authorization'	=> 'Bearer ' . self::$accessToken
			]
		]);

		$output = json_decode($output->getBody()->getContents());

		return $output;
	}
}
