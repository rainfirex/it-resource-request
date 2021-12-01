<?php namespace App\Services {
    use Illuminate\Support\Facades\Config;

    class LdapAuthService
    {
        private $message;

        private $host;
        private $port;
        private $domain;
        private $ldapDn;

        private $ldapConnect;

        private $bindLogin;
        private $bindPassword;

        /**
         * @return mixed
         */
        public function getMessage(){
            return $this->message;
        }

        /**
         * Установить и вернуть параметры из файла конфигурации
         * @param $host
         * @param $domain
         * @param $ldapDn
         * @param string $bindLogin
         * @param string $bindPassword
         * @return bool
         */
        public function loadConfig(&$host, &$domain, &$ldapDn, &$bindLogin = '', &$bindPassword = ''): bool {
            $option = Config::get('ldapconfig');
            $this->host   = $host = $option['host'];
            $this->port   = $host = $option['port'];
            $this->domain = $domain = $option['domain'];
            $this->ldapDn = $ldapDn = $option['ldapDn'];
            $this->bindLogin    = $bindLogin    = $option['bind_login'];
            $this->bindPassword = $bindPassword = $option['bind_password'];
            return ($option == null) ? false: true;
        }

        /**
         * Создать соединение
         * @param string $login
         * @param string $password
         * @return bool
         */
        public function ldapConnect(string $login, string $password): bool {
            $this->ldapConnect = ldap_connect($this->host, $this->port);

            if ($this->ldapConnect){
                ldap_set_option($this->ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($this->ldapConnect, LDAP_OPT_REFERRALS, 0);

                try{
                    return ldap_bind($this->ldapConnect, $this->domain.'\\'.$login, $password);
                }catch (\Exception $ex) {
                    $this->message = ($ex->getMessage() === 'ldap_bind(): Unable to bind to server: Invalid credentials') ? 'Неверные учетные данные': $ex->getMessage();
                }
            }
            return false;
        }

        /**
         * Проверить соединение
         * @param string|null $uri
         * @param int $port
         */
        public  function testConnect(string $uri = null, int $port = 389){
            if($uri == null)
                $uri = $this->host;
            $ldapConnect =  ldap_connect($uri, $port);
            if ($ldapConnect){
                $result = ldap_bind($ldapConnect, $this->ldapDn);
                if($result)
                    echo 'Соединение успешно!';
                else
                    echo 'Соединение не установлено!';
            }
            else
            {
                echo 'ldap_connect - не смог подготовить соединение';
            }
        }

        /**
         * Получить пользователся по логину
         * @param string $login
         * @return array|null
         */
        public function getUserInfo(string $login): ?array {
            //привязка LDAP прошла успешно...
            $filter = '(&(objectClass=user)(objectCategory=person)(sAMAccountName='.$login.'))';
            // $sr = ldap_search($ldapConnect, $ldapDn, $filter,  ['cn', 'dn', 'mail', 'telephonenumber', 'othertelephone', 'mobile', 'department', 'title']);
            $sr = ldap_search($this->ldapConnect, $this->ldapDn, $filter,  ['*']);
            $ldapEntries = ldap_get_entries($this->ldapConnect, $sr);
            $username = isset($ldapEntries[0]['cn']) ? $ldapEntries[0]['cn'][0] : '';
            $email = isset($ldapEntries[0]['mail']) ? $ldapEntries[0]['mail'][0] : '';
            $phone = isset($ldapEntries[0]['telephonenumber']) ? $ldapEntries[0]['telephonenumber'][0] : '';
            $mobile = isset($ldapEntries[0]['mobile']) ? $ldapEntries[0]['mobile'][0] : '';
            $othertelephone = isset($ldapEntries[0]['othertelephone']) ? $ldapEntries[0]['othertelephone'][0] : '';
            $department = isset($ldapEntries[0]['department']) ? $ldapEntries[0]['department'][0] : '';
            $title = isset($ldapEntries[0]['title']) ? $ldapEntries[0]['title'][0] : '';

            ldap_close($this->ldapConnect);

            if (empty($username)){
                $this->message = 'Пользователь не найден!';
                return null;
            } else {
                return [
                    'username' => $username,
                    'email'    => $email,
                    'phone'    => $phone,
                    'mobile'   => $mobile,
                    'title'    => $title,
                    'othertelephone' => $othertelephone,
                    'department'     => $department
                ];
            }
        }

        /**
         * Получить пользователей по имени
         * @param string $username
         * @return array
         */
        public function getUserByName(string $username)
        {
            $users = [];
            if ($this->ldapConnect) {
                $filter = '(&(objectClass=user)(objectCategory=person)(cn=*' . trim($username) . '*))'; //sAMAccountName
                $sr = ldap_search($this->ldapConnect, $this->ldapDn, $filter, ['cn', 'dn', 'mail', 'telephonenumber', 'othertelephone', 'mobile', 'department', 'title', 'samaccountname', 'company', 'l', 'streetaddress']); //* 'cn', 'dn', 'mail', 'telephonenumber', 'othertelephone', 'mobile', 'department', 'title','samaccountname', 'company', 'l', 'streetaddress'
                $ldapEntries = ldap_get_entries($this->ldapConnect, $sr);

                foreach ($ldapEntries as $entry) {
                    if (!is_array($entry)) continue;
                    $username = isset($entry['cn']) ? $entry['cn'][0] : '';
                    $login = isset($entry['samaccountname']) ? $entry['samaccountname'][0] : '';
                    $email = isset($entry['mail']) ? $entry['mail'][0] : '';
                    $phone = isset($entry['telephonenumber']) ? $entry['telephonenumber'][0] : '';
                    $mobile = isset($entry['mobile']) ? $entry['mobile'][0] : '';
                    $othertelephone = isset($entry['othertelephone']) ? $entry['othertelephone'][0] : '';
                    $department = isset($entry['department']) ? $entry['department'][0] : '';
                    $title = isset($entry['title']) ? $entry['title'][0] : '';
                    $company = isset($entry['company']) ? $entry['company'][0] : '';
                    $city = isset($entry['l']) ? $entry['l'][0] : '';
                    $street = isset($entry['streetaddress']) ? $entry['streetaddress'][0] : '';

                    $users[] = [
                        'username' => $username,
                        'login' => $this->domain . '\\' . $login,
                        'email' => $email,
                        'phone' => $phone,
                        'mobile' => $mobile,
                        'othertelephone' => $othertelephone,
                        'department' => $department,
                        'title' => $title,
                        'company' => $company,
                        'city' => $city,
                        'street' => $street
                    ];
                }
            }

            return $users;
        }
    }
}
