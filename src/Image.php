<?php

namespace AntvTech\Image;


use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Img;
use Intervention\Image\Response;

class Image {

	/**
	 * Create image by file, width, height, and format
	 *
	 * @param Request $request
	 * @param integer $w
	 * @param integer $h
	 * @param string $format
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
	 */
	public function make( Request $request ) {
		$filename = $request->get( 'file' );
		$width    = $request->get( 'w' );
		$height   = $request->get( 'h' );
		$format   = $request->get( 'format' ) ? $request->get( 'format' ) : 'jpg';

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

		return $img->response( $format );
	}

	/**
	 * Create Placeholder image
	 *
	 * @param Request $request
	 * @param integer $width
	 * @param integer $height
	 * @param string $format
	 *
	 * @return mixed
	 */
	public function placeholder( Request $request, $width, $height ) {
		$format = $request->get( 'format' ) ? $request->get( 'format' ) : 'png';

		$img = Img::cache( function ( $image ) use ( $width, $height ) {
			return $image->make( __DIR__ . '/antv.png' )
			             ->resizeCanvas( 400, 400, 'center', false )
			             ->fit( $width, $height, function ( $constraint ) {
				             $constraint->upsize();
			             } )
			             ->interlace()
			             ->greyscale();
		}, 10, true );

		return $img->response( $format );
	}
}