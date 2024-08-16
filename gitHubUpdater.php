<?php

namespace RYSE\GitHubUpdaterDemo;

/**
 * Enable WordPress to check for and update a custom plugin that's hosted in
 * either a public or private repository on GitHub.
 *
 * @package RYSE\GitHubUpdaterDemo
 * @version 1.1.1
 * 
 * @since 1.0.0
 * @author Ryan Sechrest
 *
 * @since 1.0.6
 * @author Blaine Moore
 */
class GitHubUpdater
{
    /**
     * Absolute path to plugin file containing plugin header
     *
     * @var string .../wp-content/plugins/github-updater-demo/github-updater-demo.php
     */
    private string $file = '';

    /*------------------------------------------------------------------------*/

    /**
     * GitHub URL
     *
     * @var string https://github.com/ryansechrest/github-updater-demo
     */
    private string $gitHubUrl = '';

    /**
     * GitHub path
     *
     * @var string ryansechrest/github-updater-demo
     */
    private string $gitHubPath = '';

    /**
     * GitHub organization
     *
     * @var string ryansechrest
     */
    private string $gitHubOrg = '';

    /**
     * GitHub repository
     *
     * @var string github-updater-demo
     */
    private string $gitHubRepo = '';

    /**
     * GitHub branch
     *
     * @var string main
     */
    private string $gitHubBranch = 'main';

    /**
     * GitHub access token
     *
     * @var string github_pat_fU7xGh...
     */
    private string $gitHubAccessToken = '';

    /**
     * GitHub versions array
     * 
     * @var array https://api.github.com/repos/BlaineMoore/github-updater-demo/releases
     */
    private array $gitHubVersions = [];

    /**
     * GitHub Update Method
     * 
     * @var string default
     */
    private string $gitHubUpdateMethod = 'default';

    /*------------------------------------------------------------------------*/

    /**
     * Plugin file
     *
     * @var string github-updater-demo/github-updater-demo.php
     */
    private string $pluginFile = '';

    /**
     * Plugin directory
     *
     * @var string github-updater-demo
     */
    private string $pluginDir = '';

    /**
     * Plugin filename
     *
     * @var string github-updater-demo.php
     */
    private string $pluginFilename = '';

    /**
     * Plugin slug
     *
     * @var string ryansechrest-github-updater-demo
     */
    private string $pluginSlug = '';

    /**
     * Plugin URL
     *
     * @var string https://ryansechrest.github.io/github-updater-demo
     */
    private string $pluginUrl = '';

    /**
     * Plugin version
     *
     * @var string 1.0.0
     */
    private string $pluginVersion = '';

    /**
     * Plugin Author
     * 
     * @var string Ryan Sechrest
     */
    private string $pluginAuthor = '';

    /**
     * Plugin Author URI
     * 
     * @var string https://ryansechrest.com/
     */
    private string $pluginAuthorURI = '';

    /**
     * Plugin Description
     * 
     * @var string WordPress plugin to demonstrate how `GitHubUpdater` can enable WordPress to check for and update a custom plugin that's hosted in either a public or private repository on GitHub.
     */
    private string $pluginDescription = '';

    /**
     * Plugin Name
     * 
     * @var string GitHub Updater Demo
     */
    private string $pluginName = '';

    /**
     * Plugin Icons
     * 
     * @var string [1x, 2x]
     */
    private $pluginIcons = false;

    /**
     * Plugin Banners
     * 
     * @var string [low, high]
     */
    private $pluginBanners = false;

    /*------------------------------------------------------------------------*/

    /**
     * Enable GitHubUpdate debugger.
     *
     * @var bool
     */
    private bool $enableDebugger = false;

    /**
     * Tested WordPress version.
     *
     * @var string 6.5.2
     */
    private string $testedWpVersion = '';
    
    /**
     * Plugin Minimum WP Version
     * 
     * @var string 6.5
     */
    private string $pluginMinimumWP = '6.0';

    /**
     * Plugin Minimum PHP Version
     * 
     * @var string 8.2
     */
    private string $pluginMinimumPHP = '8.2';

    /**************************************************************************/

    /**
     * Set absolute path to plugin file containing plugin header.
     *
     * @param string $file .../wp-content/plugins/github-updater-demo/github-updater-demo.php
     */
    public function __construct(string $file)
    {
        $this->file = $file;

        $this->load();
    }

    /**
     * Enable GitHubUpdater debugger.
     *
     * If this property is set to true, as well as the WP_DEBUG and WP_DEBUG_LOG
     * constants within wp-config.php, then GitHubUpdater will log pertinent
     * information to wp-content/debug.log.
     *
     * @return $this
     */
    public function enableDebugger(): self
    {
        $this->enableDebugger = true;

        return $this;
    }

    /**
     * Add update mechanism to plugin.
     *
     * @return void
     */
    public function add(): void
    {
        $this->updatePluginDetailsUrl();
        $this->checkPluginUpdates();
        $this->prepareHttpRequestArgs();
        $this->moveUpdatedPlugin();
    }

    /**************************************************************************/

    /**
     * Load properties with values based on $file.
     *
     *   $gitHubUrl       GitHub URL           https://github.com/ryansechrest/github-updater-demo
     *   $gitHubPath      GitHub path          ryansechrest/github-updater-demo
     *   $gitHubOrg       GitHub organization  ryansechrest
     *   $gitHubRepo      GitHub repository    github-updater-demo
     *   $pluginFile      Plugin file          github-updater-demo/github-updater-demo.php
     *   $pluginDir       Plugin directory     github-updater-demo
     *   $pluginFilename  Plugin filename      github-updater-demo.php
     *   $pluginSlug      Plugin slug          ryansechrest-github-updater-demo
     *   $pluginUrl       Plugin URL           https://ryansechrest.github.io/github-updater-demo
     *   $pluginVersion   Plugin version       1.0.0
     *   $pluginTested    Plugin Tested To     6.5.4
     *   $pluginBranch    Plugin Branch        main
     */
    private function load(): void
    {
        // Fields from plugin header
        $pluginData = get_file_data(
            $this->file,
            [
                'PluginURI' => 'Plugin URI',
                'Version' => 'Version',
                'TestedUpTo' => 'Tested up to',
                'UpdateURI' => 'Update URI',
                'PluginTested' => 'Tested Up To',
                'Branch' => 'Branch Name',
                'UpdateMethod' => 'Update Method',
                'Author' => 'Author',
                'AuthorURI' => 'Author URI',
                'Description' => 'Description',
                'RequiresWP' => 'Requires at least',
                'RequiresPHP' => 'Requires PHP',
                'PluginName' => 'Plugin Name',
                'Icon1xURI' => 'Icon URI',
                'Icon2xURI' => 'Icon 2x URI',
                'Banner1xURI' => 'Banner URI',
                'Banner2xURI' => 'Banner 2x URI'
            ]
        );

        // Extract fields from plugin header
        $pluginUri = $pluginData['PluginURI'] ?? '';
        $updateUri = $pluginData['UpdateURI'] ?? '';
        $version = $pluginData['Version'] ?? '';
        $pluginTested = $pluginData['PluginTested'] ?? '';
        $pluginBranch = $pluginData['Branch'] ?? 'main';
        $pluginUpdateMethod = $pluginData['UpdateMethod'] ?? 'default'; 

        $pluginAuthor = $pluginData['Author'] ?? '';
        $pluginAuthorURI = $pluginData['AuthorURI'] ?? '';
        $pluginDescription = $pluginData['Description'] ?? '';
        $pluginRequiresWP = $pluginData['RequiresWP'] ?? '6.0';
        $pluginRequiresPHP = $pluginData['RequiresPHP'] ?? '8.2';
        $pluginName = $pluginData['PluginName'] ?? '';

        // Plugin icons can be remote URLs or a relative path (from the location of this file) to a local assets directory.
        $pluginIcons = [];
        if($pluginData['Icon1xURI']) { 
            $pluginIcons['1x'] = (substr( $pluginData['Icon1xURI'],0,4) == 'http') ? $pluginData['Icon1xURI'] : plugins_url($pluginData['Icon1xURI'],__FILE__); 
        }
        if($pluginData['Icon2xURI']) { 
            $pluginIcons['2x'] = (substr( $pluginData['Icon2xURI'],0,4) == 'http') ? $pluginData['Icon2xURI'] : plugins_url($pluginData['Icon2xURI'],__FILE__); 
        }

        // Plugin icons can be remote URLs or a relative path (from the location of this file) to a local assets directory.
        $pluginBanners = [];
        if($pluginData['Banner1xURI']) { 
            $pluginBanners['low'] = (substr( $pluginData['Banner1xURI'],0,4 )=='http') ? $pluginData['Banner1xURI'] : plugins_url($pluginData['Banner1xURI'],__FILE__); 
        }
        if($pluginData['Banner2xURI']) {
            $pluginBanners['high'] = (substr( $pluginData['Banner2xURI'],0,4) == 'http') ? $pluginData['Banner2xURI'] : plugins_url($pluginData['Banner2xURI'],__FILE__); 
        }
        
        // If required fields were not set, exit
        if (!$pluginUri || !$updateUri || !$version || !$pluginName) {
            $this->addAdminNotice('Plugin <b>%s</b> is missing one or more required header fields: <b>Plugin Name</b>, <b>Plugin URI</b>, <b>Version</b>, and/or <b>Update URI</b>.');
            return;
        }

        // e.g. `https://github.com/ryansechrest/github-updater-demo`
        $this->gitHubUrl = $updateUri;

        // e.g. `ryansechrest/github-updater-demo`
        $this->gitHubPath = trim(
            wp_parse_url($updateUri, PHP_URL_PATH),
            '/'
        );

        // e.g. `ryansechrest` and `github-updater-demo`
        [$this->gitHubOrg, $this->gitHubRepo] = explode(
            '/', $this->gitHubPath
        );

        // e.g. `github-updater-demo/github-updater-demo.php`
        $this->pluginFile = str_replace(
            WP_PLUGIN_DIR . '/', '', $this->file
        );

        // e.g. `github-updater-demo` and `github-updater-demo.php`
        [$this->pluginDir, $this->pluginFilename] = explode(
            '/', $this->pluginFile
        );

        // e.g. `ryansechrest-github-updater-demo`
        $this->pluginSlug = sprintf(
            '%s-%s', $this->gitHubOrg, $this->gitHubRepo
        );

        // e.g. `https://ryansechrest.github.io/github-updater-demo`
        $this->pluginUrl = $pluginUri;

        // e.g. `1.0.0`
        $this->pluginVersion = $version;

        // e.g. `6.5.4`
        $this->testedWpVersion = $pluginTested;

        // e.g. `main` or `master`
        if('' != $pluginBranch) { $this->gitHubBranch = $pluginBranch; }

        // e.g. `default` or `versions` or `branch`
        if('' != $pluginUpdateMethod) { $this->gitHubUpdateMethod = $pluginUpdateMethod; }

        // e.g. `Ryan Sechrest`
        $this->pluginAuthor = $pluginAuthor;

        // e.g. `https://ryansechrest.com/`
        $this->pluginAuthorURI = $pluginAuthorURI;

        // e.g. `WordPress plugin to demonstrate how `GitHubUpdater` can enable WordPress to check for and update a custom plugin that's hosted in either a public or private repository on GitHub.`
        $this->pluginDescription = $pluginDescription;

        // e.g. `6.5`
        $this->pluginMinimumWP = $pluginRequiresWP;

        // e.g. `8.2`
        $this->pluginMinimumPHP = $pluginRequiresPHP;

        // e.g. `GitHub Updater Demo`
        $this->pluginName = $pluginName;

        if($pluginIcons) { $this->pluginIcons = $pluginIcons; }
        if($pluginBanners) { $this->pluginBanners = $pluginBanners; }
        
    }

    /*------------------------------------------------------------------------*/

    /**
     * Log message with optional value.
     *
     * @param string $message Plugins data
     * @return void
     */
    private function log(string $message,): void
    {
        if (!$this->enableDebugger || !WP_DEBUG || !WP_DEBUG_LOG) return;

        error_log('[GitHubUpdater] ' . $message);
    }

    /**
     * Log when method starts running.
     *
     * @param string $method _checkPluginUpdates
     * @param string $hook update_plugins_github.com
     * @return void
     */
    private function logStart(string $method, string $hook = ''): void
    {
        $message = $method . '() ';

        if ($hook) $message = $hook . ' → ' . $message;

        $this->log($message);
        $this->log(str_repeat('-', 50));
    }

    /**
     * Log label and value through print_r().
     *
     * @param string $label $pluginData
     * @param mixed $value ['version' => '1.0.0', ...]
     * @return void
     */
    private function logValue(string $label, mixed $value): void
    {
        if (!is_string($value)) {
            $value = var_export($value, true);
        }

        $this->log($label . ': ' . $value);
    }

    /**
     * Log when method finishes running.
     *
     * @param string $method _checkPluginUpdates
     * @return void
     */
    private function logFinish(string $method): void
    {
        $this->log('/ ' . $method . '()');
        $this->log('');
    }

    /*------------------------------------------------------------------------*/

    /**
     * Add admin notice that required plugin header fields are missing.
     *
     * @param string $message Plugin <b>%s</b> is missing one or more required header fields: <b>Plugin URI</b>, <b>Version</b>, and/or <b>Update URI</b>.
     * @return void
     */
    private function addAdminNotice(string $message): void
    {
        add_action('admin_notices', function () use ($message) {
            $pluginFile = str_replace(
                WP_PLUGIN_DIR . '/', '', $this->file
            );
            echo '<div class="notice notice-error">';
            echo '<p>';
            echo wp_kses(
                sprintf($message, $pluginFile),
                ['b' => []]
            );
            echo '</p>';
            echo '</div>';
        });
    }

    /*------------------------------------------------------------------------*/

    /**
     * Update plugin details URL.
     *
     * If we don't set `slug` in the plugin response within
     * `_checkPluginUpdates()`, a PHP warning appears on Dashboard > Updates,
     * triggered in `wp-admin/update-core.php on line 570`, that the `slug` is
     * missing.
     *
     * Since we're forced to set the `slug`, WordPress thinks the plugin is
     * hosted on wordpress.org, and attempts to show plugin details from
     * wordpress.org in its modal, however this results in an error:
     * `Plugin not found.`
     *
     * We use this filter to replace the WordPress modal URL with the value
     * of the `Update URI` plugin header, which fixes the URL in the following
     * places:
     *
     * Dashboard > Updates
     *
     *   [View version X.Y.Z details]
     *
     * Plugins
     *
     *   [View details]
     *   [View version X.Y.Z details]
     *
     * @return void
     */
    private function updatePluginDetailsUrl(): void
    {
        add_filter(
            'plugins_api',
            [$this, '_updatePluginDetailsUrl'],
            10,
            3
        );
    }

    /**
     * Hook to update plugin details.
     *
     * @param array|false|object $result The result object or array. Default false.
     * @param string             $action The type of information being requested from the Plugin Installation API.
     * @param object             $args   Plugin API arguments.
     */
    public function _updatePluginDetailsUrl(bool $result, string $action, object $args)
    {
        if ('plugin_information' !== $action || empty($args->slug)) {
            return false;
        }

        if ($args->slug == current(explode('/', $this->pluginSlug))) {

            // Save plugin version from remote plugin file or latest version, e.g. `1.1.0`
            $newVersion = $this->getRemoteVersion();

            // If version wasn't found, exit
            if (!$newVersion) return false;

            // Build plugin response for WordPress
            $plugin = [
                'name' => $this->pluginName,
                'slug' => $this->pluginSlug,
                'requires' => $this->pluginMinimumWP,
                'tested' => $this->testedWpVersion,
                'version' => $newVersion,
                'author' => $this->pluginAuthor,
                'author_profile' => $this->pluginAuthorURI,

                'homepage' => $this->pluginUrl,
                'plugin' => $this->pluginFile,
                'short_description' => $this->pluginDescription,
                'sections' => [
                    'Description' => $this->replaceMarkDown($this->pluginDescription),
                    'Changelog' => $this->getPluginChangeLog(),
                ],
                'download_link' => $this->getRemotePluginZipFile(),
            ];
            if($this->pluginIcons) { $plugin['icons'] = $this->pluginIcons; }
            if($this->pluginBanners) { $plugin['banners'] = $this->pluginBanners; }
            
            //$plugin['sections']['Debug'] = implode('<br /><br />',$plugin['banners']);
            return (object) $plugin;
        }

        return $result;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Check for plugin updates.
     *
     * If plugin has an `Update URI` pointing to `github.com`, then check if
     * plugin was updated on GitHub, and if so, record a pending update so that
     * either WordPress can automatically update it (if enabled), or a user can
     * manually update it much like an officially-hosted plugin.
     *
     * @return void
     */
    private function checkPluginUpdates(): void
    {
        add_filter(
            'update_plugins_github.com',
            [$this, '_checkPluginUpdates'],
            10,
            3
        );
    }

    /**
     * Hook to check for plugin updates.
     *
     *   $update  Plugin update data with the latest details.
     *   $data    Plugin data as defined in plugin header.
     *   $file    Plugin file, e.g. `github-updater-demo/github-updater-demo.php`
     *
     * @param array|false $update false
     * @param array $data ['PluginName' => 'GitHub Updater Demo', ...]
     * @param string $file github-updater-demo/github-updater-demo.php
     * @return array|false
     */
    public function _checkPluginUpdates(
        array|false $update, array $data, string $file
    ): array|false
    {
        // If plugin does not match this plugin, exit
        if ($file !== $this->pluginFile) return $update;

        $this->logStart(
            '_checkPluginUpdates', 'update_plugins_github.com'
        );

        // Save plugin version from remote plugin file or latest version, e.g. `1.1.0`
        $newVersion = $this->getRemoteVersion();

        $this->log('Does $newVersion (' . $newVersion . ') exist...');

        // If version wasn't found, exit
        if (!$newVersion) {
            $this->log('No');
            $this->logValue('Return early', $update);
            $this->logFinish('_checkPluginUpdates');

            return $update;
        }

        $this->log('Yes');

        // Build plugin response for WordPress
        $plugin = [
            'name' => $this->pluginName,
            'slug' => $this->pluginSlug,
            'requires' => $this->pluginMinimumWP,
            'tested' => $this->testedWpVersion,
            'version' => $newVersion,
            'author' => $this->pluginAuthor,
            'author_profile' => $this->pluginAuthorURI,

            'homepage' => $this->pluginUrl,
            'plugin' => $this->pluginFile,
            'short_description' => $this->pluginDescription,
            'download_link' => $this->getRemotePluginZipFile(),
        ];
        if($this->pluginIcons) { 
            $this->log('Plugin Icons exist.');
            $plugin['icons'] = $this->pluginIcons; 
        }

        $this->logValue('Return', $plugin);
        $this->logFInish('_checkPluginUpdates');

        return $plugin;
    }

    /**
     * Get the latest version from GitHub
     * 
     * @return string
     */
    private function getRemoteVersion(): string 
    {
        switch($this->gitHubUpdateMethod) {
            case 'default':
            case 'versions':
                $this->loadRepositoryVersions();
                // If there are versions defined, return most recent version number
                if(0<count($this->gitHubVersions)) {
                    return $this->gitHubVersions[0]->tag_name;
                } elseif($this->gitHubUpdateMethod=='versions') {
                    break; // Don't continue to the branch
                }
            case 'branch':
                // Get remote plugin file contents to read plugin header
                $fileContents = $this->getRemotePluginFileContents();

                // Extract plugin version from remote plugin file contents
                preg_match_all(
                    '/\s+\*\s+Version:\s+(\d+(\.\d+){0,2})/',
                    $fileContents,
                    $matches
                );

                // Save plugin version from remote plugin file, e.g. `1.1.0`
                return $matches[1][0] ?? '';
        }
        return '';
    }

    /**
     * Get remote plugin file contents from GitHub repository.
     *
     * @return string
     */
    private function getRemotePluginFileContents(): string
    {
        return $this->gitHubAccessToken
            ? $this->getPrivateRemotePluginFileContents()
            : $this->getPublicRemotePluginFileContents();
    }

    /**
     * Get remote plugin file contents from public GitHub repository.
     *
     * @return string
     */
    private function getPublicRemotePluginFileContents(): string
    {
        // Get public remote plugin file containing plugin header,
        // e.g. `https://raw.githubusercontent.com/ryansechrest/github-updater-demo/master/github-updater-demo.php`
        $remoteFile = $this->getPublicRemotePluginFile($this->pluginFilename);

        return wp_remote_retrieve_body(wp_remote_get($remoteFile));
    }

    /**
     * Get public remote plugin file.
     *
     * @param string $filename github-updater-demo.php
     * @return string https://raw.githubusercontent.com/ryansechrest/github-updater-demo/master/github-updater-demo.php
     */
    private function getPublicRemotePluginFile(string $filename): string
    {
        // Generate URL to public remote plugin file.
        return sprintf(
            'https://raw.githubusercontent.com/%s/%s/%s',
            $this->gitHubPath,
            $this->gitHubBranch,
            $filename
        );
    }

    /**
     * Get remote plugin file contents from private GitHub repository.
     *
     * @return string
     */
    private function getPrivateRemotePluginFileContents(): string
    {
        // Get public remote plugin file containing plugin header,
        // e.g. `https://api.github.com/repos/ryansechrest/github-updater-demo/contents/github-updater-demo.php?ref=master`
        $remoteFile = $this->getPrivateRemotePluginFile($this->pluginFilename);

        return wp_remote_retrieve_body(
            wp_remote_get(
                $remoteFile,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->gitHubAccessToken,
                        'Accept' => 'application/vnd.github.raw+json',
                    ]
                ]
            )
        );
    }

    /**
     * Get private remote plugin file.
     *
     * @param string $filename github-updater-demo.php
     * @return string https://api.github.com/repos/ryansechrest/github-updater-demo/contents/github-updater-demo.php?ref=master
     */
    private function getPrivateRemotePluginFile(string $filename): string
    {
        // Generate URL to private remote plugin file.
        return sprintf(
            'https://api.github.com/repos/%s/contents/%s?ref=%s',
            $this->gitHubPath,
            $filename,
            $this->gitHubBranch
        );
    }

    /**
     * Get path to remote plugin ZIP file.
     *
     * @return string https://github.com/ryansechrest/github-updater-demo/archive/refs/heads/master.zip
     */
    private function getRemotePluginZipFile(): string
    {
        return $this->gitHubAccessToken
            ? $this->getPrivateRemotePluginZipFile()
            : $this->getPublicRemotePluginZipFile();
    }

    /**
     * Get path to public remote plugin ZIP file.
     *
     * @return string https://github.com/ryansechrest/github-updater-demo/archive/refs/heads/master.zip
     */
    private function getPublicRemotePluginZipFile(): string
    {
        switch($this->gitHubUpdateMethod) {
            case 'default':
            case 'versions':
                $this->loadRepositoryVersions();
                // If there are versions defined, return most recent zipball
                if(0<count($this->gitHubVersions)) {
                    return $this->gitHubVersions[0]->zipball_url;
                } elseif($this->gitHubUpdateMethod=='versions') {
                    break; // Don't continue to the branch
                }
            case 'branch':
                return sprintf(
                    'https://github.com/%s/archive/refs/heads/%s.zip',
                    $this->gitHubPath,
                    $this->gitHubBranch
                );
        }
        return ''; // Error finding zip file
    }

    /**
     * Get path to private remote plugin ZIP file.
     *
     * @return string https://api.github.com/repos/ryansechrest/github-updater-demo/zipball/master
     */
    private function getPrivateRemotePluginZipFile(): string
    {
        switch($this->gitHubUpdateMethod) {
            case 'default':
            case 'versions':
                //$this->loadRepositoryVersions();
                // If there are versions defined, return most recent zipball
                if(0<count($this->gitHubVersions)) {
                	if($this->gitHubAccessToken) {
                		$this->gitHubVersions[0]->zipball_url = add_query_arg('access_token', $this->gitHubAccessToken, $this->gitHubVersions[0]->zipball_url);
                	}
                    return $this->gitHubVersions[0]->zipball_url;
                } elseif($this->gitHubUpdateMethod=='versions') {
                    break; // Don't continue to the branch
                }
            case 'branch':
                return sprintf(
                    'https://api.github.com/repos/%s/zipball/%s',
                    $this->gitHubPath,
                    $this->gitHubBranch
                );        
        }
        return ''; // Error finding zip file
    }

    /**
     * Loads an array of versions from the GitHub API.
     */
    private function loadRepositoryVersions(): void
    {
        if(null === $this->gitHubVersions) { $this->log('Null GHV'); $this->logFinish('loadRepositoryVersions'); return; }
        if(0 < count($this->gitHubVersions)) { $this->log('GHV Already Loaded'); $this->logFinish('loadRepositoryVersions'); return; }

        $headers = [ 'Accept' => 'application/vnd.github+json', ];
        if($this->gitHubAccessToken) { $headers['Authorization'] = 'Bearer ' . $this->gitHubAccessToken; }

        $args = [
            'method' => 'GET',
            'timeout' => 5,
            'redirection' => 5,
            'httpversion' => '1.0',
            'headers' => $headers,
            'sslverify' => true,
        ];
        $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases',
                            $this->gitHubOrg,
                            $this->gitHubRepo);
        
        $this->gitHubVersions = json_decode(wp_remote_retrieve_body(wp_remote_get($request_uri, $args)));
    }

    /**
     * Creates an HTML changelog of updates, using versions from the repository if present 
     * or the plugin URI if not.
     * 
     * @return string "<em>Changelog not currently available.</em>"
     */

    private function getPluginChangeLog(): string 
    {
        $this->loadRepositoryVersions();
        
        $changeLog = '<em>Changelog not currently available.</em>';
        if(0 < count($this->gitHubVersions)) {
            $changeLog = '';
            foreach($this->gitHubVersions as $version) {
                $changeLog .= "<h4>".$version->name." (".
                            wp_date( get_option( 'date_format' ), strtotime($version->published_at) ).")</h4>\n".
                            $this->replaceMarkDown($version->body)."\n<br />\n";
            }
        } elseif(strlen($this->pluginUrl) > 0) {
            $changeLog = wp_remote_request($this->pluginUrl)['body'];
        }

        return $changeLog;
    }

    /**
     * Replaces the basic GitHub markdown syntax with HTML.
     * 
     * Not all HTML is supported, so only limited syntax is implemented.
     * 
     * @return string 'Version 1.0.8'
     */
    private function replaceMarkDown($md): string 
    {
        $patterns = array(); $replacements = array();
        $md = "$md\r\n"; // Patterns less likely to break w/a blank line at the end
                
        $patterns[] = '/^[\*-]\s+(.+)\s/m'; // Unordered List Items
        $replacements[] = '<li>$1</li>';
        $patterns[] = '/(<li>.*?<\/li>\s*)+/s'; // Unordered List Grouping
        $replacements[] = "<ul>$0</ul>\n";
        $patterns[] = '/^(\d+\.)\s+(.+)\s/m'; // Ordered Lists (not officially supported)
        $replacements[] = '<olli>$2</olli>';
        $patterns[] = '/(<olli>.*?<\/olli>\s*)+/s'; // Ordered List Groups
        $replacements[] = "<ul>$0</ul>\n";
        $patterns[] = '/olli>/'; // Turning unordered list items into list items
        $replacements[] = 'li>';
        $patterns[] = '/\s*(<.(li|ul)>)/'; // List Stragglers
        $replacements[] = '$1';

        $patterns[] = '/> (.+)/'; // Blockquotes
        $replacements[] = '<blockquote>$1</blockquote>';
        $patterns[] = '/\s*<.blockquote>/'; // Blockquote Stragglers
        $replacements[] = '</blockquote>';
        $patterns[] = '/<.blockquote>\n<blockquote>/'; // Multi-Line Blockquotes
        $replacements[] = '<br />';
        
        // H1-H6 markdown intentionally not included, but would go here.

        $patterns[] = '/^([^<\r\n]+)/m'; // Paragraphs
        $replacements[] = '<p>$1</p>';

        $patterns[] = '/(https:\/\/[\/\w\d\-\.\+\?\&\%]+)/'; // Links
        $replacements[] = '<a href="$1">$1</a>';
        $patterns[] = '/\[(.+)\]\((<a href=[^>]+>).+<.a>\)/'; // Named Links
        $replacements[] = '$2$1</a>';

        $patterns[] = '/\*\*([^\*]+)\*\*/'; // Bold/strong text
        $replacements[] = '<strong>$1</strong>';

        $patterns[] = '/_([^\_]+)_/'; // Italics/emphasis text
        $replacements[] = '<em>$1</em>';

        $patterns[] = '/`([^\`]+)`/'; // Code
        $replacements[] = '<code>$1</code>';

        //echo "$md\n-----\n".preg_replace($patterns, $replacements, $md)."\n\n-------------------\n\n";
        return preg_replace($patterns, $replacements, $md); 
    }

    /*------------------------------------------------------------------------*/

    /**
     * Prepare HTTP request args.
     *
     * Include GitHub access token in request header when repository is private
     * so that WordPress has access to download the remote plugin ZIP file.
     *
     * @return void
     */
    private function prepareHttpRequestArgs(): void
    {
        add_filter(
            'http_request_args',
            [$this, '_prepareHttpRequestArgs'],
            10,
            2
        );
    }

    /**
     * Hook to prepare HTTP request args.
     *
     *   $args  An array of HTTP request arguments.
     *   $url   The request URL.
     *
     * @param array $args ['method' => 'GET', 'headers' => [], ...]
     * @param string $url https://api.github.com/repos/ryansechrest/github-updater-demo/zipball/master
     * @return array ['headers' => ['Authorization => 'Bearer...'], ...]
     */
    public function _prepareHttpRequestArgs(array $args, string $url): array
    {
        $this->logStart('_prepareHttpRequestArgs', 'http_request_args');
        $parsed_url = wp_parse_url($url);
        parse_str($parsed_url['query'], $parsed_query);
        $this->logValue('Access Token:',$this->gitHubAccessToken);
        $this->logValue('Host Substr:',substr(($parsed_url['host']??''),-10));
        $this->logValue('$parsed_url',$parsed_url);
        $this->logValue('args',$args);
        if( ((strpos(($parsed_url['host'] ?? ""), 'github.com') !== false) && $this->gitHubAccessToken) ||
        	isset($parsed_query['access_token'])
          ) {
        //if(('github.com' === substr(($parsed_url['host']??''),-10))&&$this->gitHubAccessToken) {
            // Include GitHub access token and file type
            $this->log('Adding Bearer Header');
            $args['headers']['Authorization'] = 'Bearer ' . $this->gitHubAccessToken;
            $args['headers']['Accept'] = 'application/vnd.github+json';
        } else {
        	$this->log('Not Adding Header');
        }
        $this->logFinish('_prepareHttpRequestArgs');

        return $args;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Move updated plugin.
     *
     * The updated plugin will be extracted into a directory containing GitHub's
     * branch name (e.g. `github-updater-demo-master`). Since this likely differs from
     * the old plugin (e.g. `github-updater-demo`), it will cause WordPress to
     * deactivate it. In order to prevent this, we move the new plugin to the
     * old plugin's directory.
     *
     * @return void
     */
    private function moveUpdatedPlugin(): void
    {
        add_filter(
            'upgrader_install_package_result',
            [$this, '_moveUpdatedPlugin'],
            10,
            2
        );
    }

    /**
     * Hook to move updated plugin.
     *
     * @param array $result ['destination' => '.../wp-content/plugins/github-updater-demo-master', ...]
     * @param array $options ['plugin' => 'github-updater-demo/github-updater-demo.php', ...]
     * @return array
     */
    public function _moveUpdatedPlugin(array $result, array $options): array
    {
        // Get plugin being updated
        // e.g. `github-updater-demo/github-updater-demo.php`
        $pluginFile = $options['plugin'] ?? '';

        // If plugin does not match this plugin, exit
        if ($pluginFile !== $this->pluginFile) return $result;

        $this->logStart(
            '_moveUpdatedPlugin', 'upgrader_install_package_result'
        );

        // Save path to new plugin
        // e.g. `.../wp-content/plugins/github-updater-demo-master`
        $newPluginPath = $result['destination'] ?? '';

        $this->log(
            'Does $newPluginPath (' . $newPluginPath . ') exist...'
        );

        // If path to new plugin doesn't exist, exit
        if (!$newPluginPath) {
            $this->log('No');
            $this->logValue('Return early', $result);
            $this->logFinish('_moveUpdatedPlugin');

            return $result;
        }

        $this->log('Yes');

        // Save root path to all plugins, e.g. `.../wp-content/plugins`
        $pluginRootPath = $result['local_destination'] ?? WP_PLUGIN_DIR;

        // Piece together path to old plugin,
        // e.g. `.../wp-content/plugins/github-updater-demo`
        $oldPluginPath = $pluginRootPath . '/' . $this->pluginDir;

        // Move new plugin to old plugin directory
        move_dir($newPluginPath, $oldPluginPath);

        // Update result based on changes above
        // destination:         `.../wp-content/plugins/github-updater-demo`
        // destination_name:    `github-updater-demo`
        // remote_destination:  `.../wp-content/plugins/github-updater-demo`
        $result['destination'] = $oldPluginPath;
        $result['destination_name'] = $this->pluginDir;
        $result['remote_destination'] = $oldPluginPath;

        $this->logValue('Return', $result);
        $this->logFinish('_moveUpdatedPlugin');

        return $result;
    }

    /**************************************************************************/

    /**
     * Set GitHub branch of plugin.
     *
     * @param string $branch main
     * @return $this
     */
    public function setBranch(string $branch): self
    {
        $this->gitHubBranch = $branch;

        return $this;
    }

    /**
     * Set GitHub Update Method of plugin.
     *
     * @param string $updateMethod default
     * @return $this
     */
    public function setUpdateMethod(string $updateMethod): self
    {
        $this->gitHubUpdateMethod = $updateMethod;

        return $this;
    }

    /**
     * Set GitHub access token.
     *
     * @param string $accessToken github_pat_fU7xGh...
     * @return $this
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->gitHubAccessToken = $accessToken;

        return $this;
    }

    /**
     * Set tested WordPress version.
     *
     * @param string $version 6.5.2
     * @return $this
     */
    public function setTestedWpVersion(string $version): self
    {
        $this->testedWpVersion = $version;

        return $this;
    }
}
