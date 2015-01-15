<?php
namespace compact\filesystem;

use compact\logging\Logger;

/**
 * Gets the mime type of a file based on the extension
 *
 * @package filesystem
 * @subpackage helper
 */
class Mimetype
{

    private static $mimes = array(/* /*/
			"" => "application/octet-stream" ,/* /*/
			"bak" => "application/octet-stream" ,/* /*/
			"bin" => "application/octet-stream" , /* /*/
			"buildpath" => "application/octet-stream" ,/* /*/
			"cex" => "text/plain" , /* /*/
			"cpt" => "application/mac-compactpro" , /* /*/
			"csv" => "text/csv" , /* /*/
			"db" => "text/plain" , /* /*/
			"dms" => "application/octet-stream" , /* /*/
			"doc" => "application/msword" , /* /*/
			"epf" => "application/octet-stream" ,/* /*/
			"exe" => "application/octet-stream" , /* /*/
			"ez" => "application/andrew-inset" , /* /*/
			"hqx" => "application/mac-binhex40",/* /*/
			"ico" => "text/plain" , /* /*/
			"ini" => "text/plain" , /* /*/
			"jpg" => "image/jpeg", /* /*/
			"lha" => "application/octet-stream" , /* /*/
			"lzh" => "application/octet-stream" , /* /*/
			"ppt" => "application/octet-stream" , /* /*/
			"psd" => "application/octet-stream" , /* /*/
			"prefs" => "text/plain" , /* /*/
			"project" => "text/plain" , /* /*/
			"sql" => "text/plain" , /* /*/
			"tmp" => "text/plain" , /* /*/
			"tpl" => "text/plain" , /* /*/
			"class" => "application/octet-stream" , /* /*/
			"so" => "application/octet-stream" , /* /*/
			"dll" => "application/octet-stream" ,/* /*/
			"oda" => "application/oda" , /* /*/
			"pdf" => "application/pdf" , /* /*/
			"ai" => "application/postscript" , /* /*/
			"eps" => "application/postscript" , /* /*/
			"ps" => "application/postscript" , /* /*/
			"smi" => "application/smil" , /* /*/
			"smil" => "application/smil" , /* /*/
			"wbxml" => "application/vnd.wap.wbxml" , /* /*/
			"wmlc" => "application/vnd.wap.wmlc" , /* /*/
			"wmlsc" => "application/vnd.wap.wmlscriptc" ,/* /*/
			"bcpio" => "application/x-bcpio" , /* /*/
			"vcd" => "application/x-cdlink" , /* /*/
			"pgn" => "application/x-chess-pgn" , /* /*/
			"cpio" => "application/x-cpio",
        "csh" => "application/x-csh",
        "dcr" => "application/x-director",
        "dir" => "application/x-director",
        "dxr" => "application/x-director",
        "dvi" => "application/x-dvi",
        "spl" => "application/x-futuresplash",
        "gtar" => "application/x-gtar",
        "hdf" => "application/x-hdf",
        "js" => "application/x-javascript",
        "skp" => "application/x-koan",
        "skd" => "application/x-koan",
        "skt" => "application/x-koan",
        "skm" => "application/x-koan",
        "latex" => "application/x-latex",
        "nc" => "application/x-netcdf",
        "cdf" => "application/x-netcdf",
        "sh" => "application/x-sh",
        "shar" => "application/x-shar",
        "swf" => "application/x-shockwave-flash",
        "sit" => "application/x-stuffit",
        "sv4cpio" => "application/x-sv4cpio",
        "sv4crc" => "application/x-sv4crc",
        "tar" => "application/x-tar",
        "tcl" => "application/x-tcl",
        "tex" => "application/x-tex",
        "texinfo" => "application/x-texinfo",
        "texi" => "application/x-texinfo",
        "t" => "application/x-troff",
        "tr" => "application/x-troff",
        "roff" => "application/x-troff",
        "man" => "application/x-troff-man",
        "me" => "application/x-troff-me",
        "ms" => "application/x-troff-ms",
        "ustar" => "application/x-ustar",
        "src" => "application/x-wais-source",
        "xhtml" => "application/xhtml+xml",
        "xht" => "application/xhtml+xml",
        "zip" => "application/zip",
        "au" => "audio/basic",
        "snd" => "audio/basic",
        "mid" => "audio/midi",
        "midi" => "audio/midi",
        "kar" => "audio/midi",
        "mpga" => "audio/mpeg",
        "mp2" => "audio/mpeg",
        "mp3" => "audio/mpeg",
        "aif" => "audio/x-aiff",
        "aiff" => "audio/x-aiff",
        "aifc" => "audio/x-aiff",
        "m3u" => "audio/x-mpegurl",
        "ram" => "audio/x-pn-realaudio",
        "rm" => "audio/x-pn-realaudio",
        "rpm" => "audio/x-pn-realaudio-plugin",
        "ra" => "audio/x-realaudio",
        "wav" => "audio/x-wav",
        "pdb" => "chemical/x-pdb",
        "xyz" => "chemical/x-xyz",
        "bmp" => "image/bmp",
        "gif" => "image/gif",
        "ief" => "image/ief",
        "jpeg" => "image/jpeg",
        "jpe" => "image/jpeg",
        "png" => "image/png",
        "tiff" => "image/tiff",
        "tif" => "image/tif",
        "djvu" => "image/vnd.djvu",
        "djv" => "image/vnd.djvu",
        "wbmp" => "image/vnd.wap.wbmp",
        "ras" => "image/x-cmu-raster",
        "pnm" => "image/x-portable-anymap",
        "pbm" => "image/x-portable-bitmap",
        "pgm" => "image/x-portable-graymap",
        "ppm" => "image/x-portable-pixmap",
        "rgb" => "image/x-rgb",
        "xbm" => "image/x-xbitmap",
        "xpm" => "image/x-xpixmap",
        "xwd" => "image/x-windowdump",
        "igs" => "model/iges",
        "iges" => "model/iges",
        "msh" => "model/mesh",
        "mesh" => "model/mesh",
        "silo" => "model/mesh",
        "wrl" => "model/vrml",
        "vrml" => "model/vrml",
        "css" => "text/css",
        "html" => "text/html",
        "htm" => "text/html",
        "asc" => "text/plain",
        "txt" => "text/plain",
        "rtx" => "text/richtext",
        "rtf" => "text/rtf",
        "sgml" => "text/sgml",
        "sgm" => "text/sgml",
        "tsv" => "text/tab-seperated-values",
        "wml" => "text/vnd.wap.wml",
        "wmls" => "text/vnd.wap.wmlscript",
        "etx" => "text/x-setext",
        "xml" => "text/xml",
        "xsl" => "text/xml",
        "mpeg" => "video/mpeg",
        "mpg" => "video/mpeg",
        "mpe" => "video/mpeg",
        "qt" => "video/quicktime",
        "mov" => "video/quicktime",
        "mxu" => "video/vnd.mpegurl",
        "avi" => "video/x-msvideo",
        "movie" => "video/x-sgi-movie",
        "php" => "text/plain",
        "ice" => "x-conference-xcooltalk",
        "log" => "text/plain",
        "cache" => "text/plain",
        "png" => "image/png"
    );

    /**
     *
     * @var Mimetype
     */
    private static $instance;

    public function __construct()
    {
        self::$instance = $this;
    }

    /**
     *
     * @return Mimetype
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new Mimetype();
        }
        
        return self::$instance;
    }

    /**
     * Returns the mime type
     *
     * @param SplFileInfo $aFile            
     *
     * @return String
     */
    public function getType(\SplFileInfo $aFile)
    {
        return $this->findType(Filesystem::getExtension($aFile));
    }

    /**
     * Returns the mime type
     *
     * @param string $aExtension            
     *
     * @return string
     */
    public function getTypeForExtension($aExtension)
    {
        return $this->findType($aExtension);
    }

    /**
     * Gets the mime type from the array
     *
     * @param String $extension            
     * @return String
     */
    private function findType($aExtension)
    {
        $aExtension = strtolower($aExtension);
        
        if (array_key_exists($aExtension, self::$mimes)) {
            $mime = self::$mimes[$aExtension];
        } else {
            Logger::get()->logWarning("Could not find mimetype for extension " . $aExtension);
            $mime = 'application/octet-stream';
        }
        
        return $mime;
    }
}
