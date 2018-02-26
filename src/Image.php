<?php

namespace AntvTech\Image;


use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Img;
use Intervention\Image\Response;

class Image {
	public function make( Request $request ) {
		$filename = $request->get( 'file' );
		$width    = $request->get( 'w' );
		$height   = $request->get( 'h' );

		if ( ! $filename ) {
			return response( '', 404 );
		}

		if ( $width ) {
			$img = Img::cache( function ( $image ) use ( $width, $height ) {
				return $image->make( $filename )
				             ->fit( $width, $height, function ( $constraint ) {
					             $constraint->upsize();
				             } )
				             ->interlace();
			}, 10, true );
		} else {
			$img = Img::cache( function ( $image ) use ( $width, $height ) {
				return $image->make( $filename )->interlace();;
			}, 10, true );
		}

		return $img->response( 'jpg' );
	}

	public function placeholder( Request $request, $width, $height ) {
		$img = Img::cache( function ( $image ) use ( $width, $height ) {
			return $image->make( __DIR__ . '/antv.png' )
			             ->resizeCanvas( 400, 400, 'center', false )
			             ->fit( $width, $height, function ( $constraint ) {
				             $constraint->upsize();
			             } )
			             ->interlace()
			             ->greyscale();
		}, 10, true );

		return $img->response( 'png' );
	}
}