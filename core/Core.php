<?php
class Core
{
    protected $currentController = 'PagesController';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        /* -------------------------------
           1. CONTROLLER RESOLUTION
        -------------------------------- */
        if (!empty($url[0])) {
            // Example: /Project → ProjectController.php
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerPath = '../app/controllers/' . $controllerName . '.php';

            if (file_exists($controllerPath)) {
                $this->currentController = $controllerName;
                unset($url[0]);
            }
        }

        require_once '../app/controllers/' . $this->currentController . '.php';

        $this->currentController = new $this->currentController;

        /* -------------------------------
           2. METHOD RESOLUTION
        -------------------------------- */
        if (!empty($url[1])) {
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        /* -------------------------------
           3. PARAMETERS
        -------------------------------- */
        $this->params = $url ? array_values($url) : [];

        /* -------------------------------
           4. RUN
        -------------------------------- */
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    /* -------------------------------
       URL PARSER
    -------------------------------- */
    public function getUrl()
    {
        if (!empty($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            if ($url !== '' && $url !== 'index.php') {
                return explode('/', filter_var($url, FILTER_SANITIZE_URL));
            }
        }

        if (!empty($_SERVER['REQUEST_URI'])) {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if (strpos($path, '/SkillXchange/public') === 0) {
                $path = substr($path, strlen('/SkillXchange/public'));
            }
            $url = trim($path, '/');
            if ($url !== '' && $url !== 'index.php') {
                return explode('/', filter_var($url, FILTER_SANITIZE_URL));
            }
        }

        return [];
    }
}
