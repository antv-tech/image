<?php

namespace AntvTech\Image;


use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Img;

class Image {
	public function make( Request $request ) {
		$filename = $request->get( 'file' );
		$width    = $request->get( 'w' );
		$height   = $request->get( 'h' );
		if ( $width || $height ) {
			$img = Img::make( $filename )
			          ->fit( $width, $height, function ( $constraint ) {
				          $constraint->upsize();
			          } )
			          ->interlace();
		} else {
			$img = Img::make( $filename )->interlace();
			$img->save();
		}

		return $img->response( 'jpg' );
	}

	public function placeholder( Request $request, $width, $height ) {
		$img = Img::make( __DIR__ . '/antv.png' )
		          ->resizeCanvas( 400, 400, 'center', false )
		          ->fit( $width, $height, function ( $constraint ) {
			          $constraint->upsize();
		          } )
		          ->interlace()
		          ->greyscale();

		return $img->response( 'png' );
	}
}