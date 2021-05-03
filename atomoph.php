<?php

function clean_text( $value ) {

    // Stripslashes
    if ( get_magic_quotes_gpc() ) {
        $value = stripslashes( $value );
    }

    // Quote if not a number
    if ( !is_numeric( $value ) ) {
        // $value = str_replace( '"', '', $value );
        // $value = htmlspecialchars( strip_tags( $value ) );

        // Strip HTML Tags
        $value = strip_tags( $value );
        // Clean up things like &amp;
        $value = html_entity_decode( $value );
        // Strip out any url-encoded stuff
        $value = urldecode( $value );
        // Replace non-AlNum characters with space
        // $value = preg_replace( '#[^ก-๙a-zA-Z0-9]#u', '', $value );
        // Replace Multiple spaces with single space
        $value = preg_replace( '/[\n\r]/', ' ', $value );
        $value = preg_replace( '/ +/', ' ', $value );
        // Trim the string of leading/trailing space
        $value = trim( $value );

    }

    return $value;
}

function getImage( $html ) {

    $doc = new DOMDocument();
    $doc->loadHTML( $html );

    $tags = $doc->getElementsByTagName( 'img' );

    return $tags[0]->getAttribute( 'src' );

}

$xml = simplexml_load_file( "https://www.ato.moph.go.th/aeed/rss.xml" );

// echo $xml->channel->title[0];

// print_r($xml->channel);
$data = [];
$i    = 0;
foreach ( $xml->channel->item as $key => $item ) {
    $data[$i]['title']   = (string) $item->title;
    $data[$i]['link']    = (string) $item->link;
    $data[$i]['text']    = clean_text( $item->description );
    $data[$i]['photo']   = getImage( $item->description );
    $data[$i]['pubdate'] = (string) $item->pubDate;
    $i++;
}

print_r( $data[0] );
