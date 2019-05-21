<?php 

namespace Emamie\Atom\ApiGenerator;

use InfyOm\Generator\Common\GeneratorConfig as InfyOmGeneratorConfig;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Utils\FileUtil;

class GeneratorConfig extends InfyOmGeneratorConfig
{
	protected $package;

	public function __construct($package)
	{
		$this->package = $package;
	}

	public function loadNamespaces(CommandData &$commandData)
    {
    	parent::loadNamespaces($commandData);

    	$this->nsApp = "Emamie\\Atom";
    	$this->nsRepository = "{$this->package}\\Repository";
    	$this->nsModel = "{$this->package}\\Model";
    	$this->nsDataTables = "{$this->package}\\DataTable";
    	$this->nsModelExtend = "Illuminate\\Database\\Eloquent\\Model";
    	$this->nsApiController = "{$this->package}\\ApiController";
    	$this->nsApiRequest = "{$this->package}\\ApiRequest";
    	$this->nsRequest = "{$this->package}\\Request";
    	$this->nsRequestBase = "{$this->package}\\RequestBase";
    	$this->nsBaseController = "{$this->package}\\BaseController";
    	$this->nsController = "{$this->package}\\Controller";

    }

	public function loadPaths()
    {

    	$packages_path = base_path('packages/');// @TODO read from config, default vendor

    	$namespace = explode('\\', $this->package);

    	$packages_path .= strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $namespace[0])) . "/" . strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $namespace[1])) . "/src/";

    	parent::loadPaths();
    	
    	$this->pathRepository = $packages_path . 'app/Repository/';
    	$this->pathModel = $packages_path . 'app/Model/';
    	$this->pathDataTables = $packages_path . 'app/DataTable/';
    	$this->pathApiController = $packages_path . 'app/ApiController/';
    	$this->pathApiRequest = $packages_path . 'app/ApiRequest/';
    	$this->pathApiTests = $packages_path . 'app/ApiTest/';
    	$this->pathApiTestTraits = $packages_path . 'app/ApiTestTrait/';
    	$this->pathController = $packages_path . 'app/Controller/';
    	$this->pathRequest = $packages_path . 'app/Request/';

    	$this->pathViews = $packages_path . 'resources/views/';
    	$this->modelJsPath = $packages_path . 'resources/assets/js/models/';

    	if(!file_exists($packages_path . 'routes/' . 'api.php')){
    		FileUtil::createFile($packages_path . 'routes/', 'api.php', "<?php\n");
    	}
    	$this->pathApiRoutes = $packages_path . 'routes/' . 'api.php';

    	if(!file_exists($packages_path . 'routes/' . 'web.php')){
    		FileUtil::createFile($packages_path . 'routes/', 'web.php', "<?php\n");
    	}
    	$this->pathRoutes = $packages_path . 'routes/' . 'web.php';

    }

    public function loadDynamicVariables(CommandData &$commandData)
    {
        parent::loadDynamicVariables($commandData);
        // $commandData->addDynamicVariable('$MODEL_NAME_PLURAL_SNAKE$', $this->mSnakePlural);
        // $commandData->addDynamicVariable('$PATH_PREFIX$', str_replace('\\','\\\\', $this->package)."\\\\Controller\\\\");
    }

    public function prepareAddOns()
    {
    	parent::prepareAddOns();

        $this->addOns['swagger'] = false;
        $this->addOns['tests'] = false;
        $this->addOns['datatables'] = false;
        $this->addOns['menu.enabled'] = false;
        $this->addOns['menu.menu_file'] = 'layouts.menu';
    }
}