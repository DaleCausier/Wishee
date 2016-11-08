<?php
/* Prevent direct access through URL; '_JEXEC' defined in site root, 'index.php'. */
defined( '_JEXEC' ) or die( 'Restricted access' );

/* Define the template $baseurl */
$this->baseurl = JURI::base(true);

/* Remove Bootstrap JS */
unset($this->_scripts[JURI::root(true).'/media/jui/js/bootstrap.min.js']);

/* Create $template variable to make file path references easier */
$template = $this->baseurl.'/templates/'.$this->template;

/* Override the default Joomla branded message in the meta generator tag */
$document = JFactory::getDocument();
$document->setGenerator('Wishee Gift Planning and Sharing');

/* Add 'home' or 'default' class to <body> tag, based on whether or not home page is beind displayed */
$app = JFactory::getApplication();
$menu = $app->getMenu();
$page_class = $menu->getActive() == $menu->getDefault() ? 'home' : 'default';

/* Get site configuration paramaters from Global Configuration settings */
$config = JFactory::getConfig();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" 
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

    <head>
        <script type="text/javascript" src="<?php echo $template.'/js/jquery.js'; ?>"></script>
        <jdoc:include type="head" />
        <link rel="stylesheet" href="<?php echo $template.'/css/foundation.css'; ?>" type="text/css" />
        <link rel="stylesheet" href="<?php echo $template.'/css/font-awesome.css'; ?>" type="text/css" />
        <link rel="stylesheet" href="<?php echo $template.'/css/template.css'; ?>" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
    </head>

    <body class="<?php echo $page_class; ?>">
        
        <header>
            <div id="top-bar">
                <div class="row">
                    <div class="small-12 columns">
                        <jdoc:include type="modules" name="top-bar" />
                    </div><!-- /.small-12 -->
                </div><!-- /.row -->
            </div><!-- /#top-bar -->
            
            <div id="main-header">
                <div id="main-menu">
                    <div class="row">
                        <div class="small-12 columns">
                            <jdoc:include type="modules" name="main-menu" />
                        </div><!-- /.small-12 -->
                    </div><!-- /.row -->
                </div>
            </div>
        </header>
        
        <div id="main">
            <div class="row">
                <div class="small-12 columns">
                    <jdoc:include type="modules" name="breadcrumb" />
                    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                </div><!-- /.small-12 -->
            </div><!-- /.row -->
        </div>
        
        <footer>
            <jdoc:include type="modules" name="footer-menu" />
            <?php echo '&copy; ' . $config->get('sitename') . ' ' . date('Y'); ?>
        </footer>
        
        <script type="text/javascript" src="<?php echo $template.'/js/bootstrap.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $template.'/js/foundation.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $template.'/js/what-input.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $template.'/js/actions.js'; ?>"></script>
        
    </body>
    
</html>