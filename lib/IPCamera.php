<?php
class IPCamera {
    private $curl_handler;
    private $ip;
    private $port;
    private $username;
    private $password;

    function __construct($ip, $port = 80, $username = 'admin', $password = '') {
        $this->ip = $ip;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;

        if (!function_exists('curl_init')) {
            throw new Exception('php cURL extension must be installed and enabled');
        }

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_USERPWD, $this->username . ":" . $this->password);
    }

    public function _request($path = 'index.htm', $params = array()) {
        $url = 'http://' . $this->ip . ':' . $this->port . '/' . $path . '?' . http_build_query($params);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        $response = curl_exec($this->curl);
        $this->curl_info = curl_getinfo($this->curl);
        if ($this->curl_info['http_code']==200) {
            return $response;
        } else if ($this->curl_info['http_code']==200) {
            throw new Exception('Authentication failed');
        } else {
            throw new Exception('Failed. Http code: ' . $this->curl_info['http_code']);
        }
    }

    public function snapshot() {
        $response = $this->_request('snapshot.cgi');
        return $response;
    }

    public function status() {
        $response = $this->_request('get_status.cgi');
        $response = str_replace(array('var ', ';'), '', $response);
        $response = parse_ini_string($response);
        return $response;
    }

    /*
    APIs:
    videostream.cgi?resolution=320*240,640*480
    videostream.asf?resolution=320*240,640*480
    decoder_control.cgi
    camera_control.cgi
    reboot.cgi
    restore_factory.cgi
    get_params.cgi
    upgrade_firmware.cgi
    upgrade_htmls.cgi
    set_alias.cgi?alias='Camera Name'
    set_datetime.cgi
    set_users.cgi
    set_devices.cgi
    set_network.cgi
    set_wifi.cgi
    set_pppoe.cgi
    set_upnp.cgi
    set_ddns.cgi
    set_ftp.cgi
    set_mail.cgi
    set_alarm.cgi
    comm_write.cgi
    set_forbidden.cgi
    set_misc.cgi
    get_misc.cgi
    set_decoder.cgi
    */

}