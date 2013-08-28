<?php
/**
 * This is pastecode paste extension. It provides access to everything we need
 * to draw the paste section of the front end.
 *
 * @version 1.0
 * @author Anthony Vitacco <avitacco@iu.edu>
 */
class PasteTwigExtension extends Twig_Extension
{
    
    /**
     * get Functions
     * This returns an array of function references to expose to the
     * templates themselves
     *
     * @return array The list of functions
     */
    public function getFunctions()
    {    
        return array(
            "getLanguages" => new Twig_Function_Method($this, "getLanguages"),
            "getPrettyLanguageName" => new Twig_Function_Method($this, "getPrettyLanguageName")
        );
    }
    
    /**
     * get Globals
     * This returns a list of global variables we want to keep around
     * In our case we have the superglobals listed here because
     * they aren't otherwise available in twig
     * 
     * @return array The list of global variables
     */
    public function getGlobals()
    {    
        return array (
            "server" => $_SERVER,
            "get" => $_GET,
            "post" => $_POST
        );
    }
    
    /**
     * get Name
     * This returns the name of this class
     */
    public function getName()
    {
        return "paste";
    }
    
    /**
     *
     */
    public function getLanguages()
    {
        $languages = array(
            "abap"=> "ABAP",
            "ada" => "ADA",
            "actionscript" => "ActionScript",
            "asciidoc" => "AsciiDoc",
            "assembly_x86" => "Assembly_x86",
            "autohotkey" => "AutoHotKey",
            "batchfile" => "BatchFile",
            "c9search" => "C9Search",
            "c_cpp" => "C/C++",
            "clojure" => "Clojure",
            "cobol" => "Cobol",
            "coffee" => "CoffeeScript",
            "coldfusion" => "ColdFusion",
            "csharp" => "C#",
            "css" => "CSS",
            "curly" => "Curly",
            "d" => "D",
            "dart" => "Dart",
            "diff" => "Diff",
            "dot" => "Dot",
            "erlang" => "Erlang",
            "ejs" => "EJS",
            "forth" => "Forth",
            "ftl" => "FreeMarker",
            "glsl" => "Glsl",
            "golang" => "Go",
            "groovy" => "Groovy",
            "haml" => "HAML",
            "haskell" => "Haskell",
            "haxe" => "haXe",
            "html" => "HTML",
            "html_ruby" => "HTML (Ruby)",
            "ini" => "Ini",
            "jade" => "Jade",
            "java" => "Java",
            "javascript" => "JavaScript",
            "json" => "JSON",
            "jsoniq" => "JSONiq",
            "jsp" => "JSP",
            "jsx" => "JSX",
            "julia" => "Julia",
            "latex" => "LaTeX",
            "less" => "LESS",
            "liquid" => "Liquid",
            "lisp" => "Lisp",
            "livescript" => "LiveScript",
            "logiql" => "LogiQL",
            "lsl" => "LSL",
            "lua" => "Lua",
            "luapage" => "LuaPage",
            "lucene" => "Lucene",
            "makefile" => "Makefile",
            "matlab" => "MATLAB",
            "markdown" => "Markdown",
            "mysql" => "MySQL",
            "mushcode" => "MUSHCode",
            "objectivec" => "Objective-C",
            "ocaml" => "OCaml",
            "pascal" => "Pascal",
            "perl" => "Perl",
            "pgsql" => "pgSQL",
            "php" => "PHP",
            "powershell" => "Powershell",
            "prolog" => "Prolog",
            "properties" => "Properties",
            "python" => "Python",
            "r" => "R",
            "rdoc" => "RDoc",
            "rhtml" => "RHTML",
            "ruby" => "Ruby",
            "rust" => "Rust",
            "sass" => "SASS",
            "scad" => "SCAD",
            "scala" => "Scala",
            "scheme" => "Scheme",
            "scss" => "SCSS",
            "sh" => "SH",
            "snippets" => "snippets",
            "sql" => "SQL",
            "stylus" => "Stylus",
            "svg" => "SVG",
            "tcl" => "Tcl",
            "tex" => "Tex",
            "text" => "Text",
            "textile" => "Textile",
            "toml" => "Toml",
            "twig" => "Twig",
            "typescript" => "Typescript",
            "vbscript" => "VBScript",
            "velocity" => "Velocity",
            "xml" => "XML",
            "xquery" => "XQuery",
            "yaml" => "YAML"
        );
        return $languages;
    }
    
    public function getPrettyLanguageName($language)
    {
        $langs = $this->getLanguages();
        if (isset($langs[$language])) {
            return $langs[$language];
        } else {
            return $language;
        }
    }
}
