<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 13/12/17
 * Time: 12:43 ص
 */

namespace Jlib;


use Illuminate\Support\ServiceProvider;
use File;
use Jlib\HtmlHelper\MenuMaker\Contracts\MenuMaker;
use Jlib\HtmlHelper\MenuMaker\ModuleLinks;
use Jlib\PluginsLoader\Loader;
use Jlib\ServiceProvider\LoadModule;


class JlibServiceProvider extends ServiceProvider
{

    public $libName;
    public $DS;

    public function boot()
    {

        /*
         *  defaind some vars
         */

        $this->libName = "Jlib";
        $this->DS = DIRECTORY_SEPARATOR;

        $this->loadCommonViews();

        /*
         * set shared vars to access it form views
         * you can add edit or delete what you want
         */
        $this->sharedVarsInViews();


        /*
         * load plugin loader
         * it responsable for load plugins from plugin package
         *
         */
        $pluginsPath = __DIR__ . $this->DS . "Plugins";

        if (File::exists($pluginsPath))
            Loader::load($this, $pluginsPath);


        /*
      * load admin auth module init point class
      * it responsible  of load views, routes , controller
      * for admin login and it use config file to load some cnfgis
      * load jlib modules like auth menus pages etc...
      */

        $path =__DIR__ . $this->DS . "JModules";

        if (File::exists($path))
            LoadModule::make($this, $path)->load();


        /*
         *  load Modules for /Modules dir
         * you can add new module by copy
         * and past old one and change paths and name
         */
        LoadModule::make($this, base_path("Modules"))
//            ->setMenuMaker(ModuleLinks::instance("Modules"))
            ->load();


    }


    private function sharedVarsInViews()
    {
        foreach (JConfig()["shared"] as $k => $value)
            view()->share($k, $value);

    }

    public function jlibLoadViews($path, $namespace)
    {

        parent::loadViewsFrom(implode(DIRECTORY_SEPARATOR, explode(".", $path)), $namespace); // TODO: Change the autogenerated stub
    }

    public function loadJlibTranslationsFrom($path, $namespace)
    {
        parent::loadTranslationsFrom($path, $namespace);
    }

    public function loadJlibPublishes(array $paths, $group = null)
    {
        parent::publishes($paths, $group);
    }


    public function jlibLoadRoutes($path)
    {
        $this->loadRoutesFrom($path);
    }

    public function jlibMigrationLoad($migratePath)
    {
        $this->loadMigrationsFrom($migratePath);
    }

    private function loadCommonViews()
    {
        $this->jlibLoadViews(__DIR__ . ".UI.CommonViews", $this->libName);

        $this->loadJlibTranslationsFrom(__DIR__ . $this->DS . 'UI' . $this->DS . 'Lang', $this->libName);
        $this->loadJlibPublishes([
            __DIR__ . $this->DS . "UI" . $this->DS . 'Assets' => public_path('vendor' . $this->DS . $this->libName),
        ], $this->libName);
    }

}


//
//
//private function loadPlugins()
//{
//    $dirs = (File::directories(self::PluginsPath()));
//
//    foreach ($dirs as $dir) {
//        $jsonOptions = self::loadBootJsonFile($dir);
//        if (@$jsonOptions->active) {
//            self::getBootFileForPlugin($dir)::init($jsonOptions);
//        }
//    }
//}
//
//private static function getBootFileForPlugin($dir)
//{
//    $parts = explode("/", $dir);
//    $PluginDir = end($parts);
//    return "Plugins\\$PluginDir\BootFile";
//}
//
//private static function loadBootJsonFile($dir)
//{
//
//    if (File::exists($file = $dir . "/boot.json"))
//        return json_decode(File::get($file));
//
//
//}
//
//public function loadViews($routDirectory, $scope, $dir)
//{
//
//    if (File::exists($routDirectory)) {
//
//        foreach (File::files($dir) as $oneFile) {
//            if (strpos($oneFile->getFilename(), 'Controller.php') !== false) {
//                $dirOfViews = Str::slug(Str::snake(Str::replaceFirst('Controller.php', "", $oneFile->getFilename())));
////                    dump(($routDirectory . "/$dirOfViews"), self::getViewNameSpace($scope, $dirOfViews));
//                $this->loadViewsFrom(($routDirectory . "/$dirOfViews"), self::getViewNameSpace($scope, $dirOfViews));
//            }
//        }
//    }
//
//
//}
//
//