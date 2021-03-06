<?php

include __DIR__ . '/load.php';

function activate($app = null, $data = null)
{
    global $gtk;
    try {
        $window = $gtk->gtk_application_window_new($app);
        $gtk->gtk_window_set_title($gtk->GTK_WINDOW($window), "Window");
        $gtk->gtk_window_set_default_size($gtk->GTK_WINDOW($window), 200, 200);
        $gtk->gtk_widget_show_all($window);
    } catch(\Error $e) {
        echo $e;
    }
}

function main($argc, $argv): int
{
    global $gtk;

    $app = $gtk->gtk_application_new("org.gtk.example", 0);

    $gtk->g_signal_connect($app, "activate", $gtk->G_CALLBACK('activate'));

    $gapp = $gtk->G_APPLICATION($app);

    $status = $gtk->g_application_run($gapp, $argc, $argv);

    $gtk->g_object_unref($app);
    return $status;
}

return main($argc, $argv);
