<?php

// Unhook the profiler
update_option( 'p3-profiler_debug', false );
update_option( 'p3-profiler_debug_log', array() );
remove_action( 'shutdown', array( $p3_profiler, 'shutdown_handler' ) );

// Delete the profiles folder
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	$blogs = get_blog_list( 0, 'all' );
	foreach ( $blogs as $blog ) {
		switch_to_blog( $blog['blog_id'] );
		$uploads_dir = wp_upload_dir();
		$folder      = $uploads_dir['basedir'] . DIRECTORY_SEPARATOR . 'profiles' . DIRECTORY_SEPARATOR;
		p3_profiler_uninstall_delete_profiles_folder( $folder );

		// Remove any options
		delete_option( 'p3-profiler_disable_opcode_cache' );
		delete_option( 'p3-profiler_use_current_ip' );
		delete_option( 'p3-profiler_ip_address' );
		delete_option( 'p3-profiler_version' );
		delete_option( 'p3-profiler_cache_buster' );
		delete_option( 'p3-profiler_profiling_enabled' );
		delete_option( 'p3-profiler_debug' );
		delete_option( 'p3-profiler_debug_log' );
	}
	restore_current_blog();
} else {
	p3_profiler_uninstall_delete_profiles_folder( P3_PROFILES_PATH );

	// Remove any options
	delete_option( 'p3-profiler_disable_opcode_cache' );
	delete_option( 'p3-profiler_use_current_ip' );
	delete_option( 'p3-profiler_ip_address' );
	delete_option( 'p3-profiler_version' );
	delete_option( 'p3-profiler_cache_buster' );
	delete_option( 'p3-profiler_profiling_enabled' );
	delete_option( 'p3-profiler_debug' );
	delete_option( 'p3-profiler_debug_log' );
}

function p3_profiler_uninstall_delete_profiles_folder( $path ) {
	if ( !file_exists( $path ) )
		return;
	$dir = opendir( $path );
	while ( ( $file = readdir( $dir ) ) !== false ) {
		if ( $file != '.' && $file != '..' ) {
			unlink( $path . DIRECTORY_SEPARATOR . $file );
		}
	}
	closedir( $dir );
	rmdir( $path );
}
