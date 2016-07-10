<?php
class Mole_Sniffs_PHP_EmbeddedPhpScoperSniff implements PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return PHP_CodeSniffer_Tokens::$scopeOpeners;

    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        return true;
        // Check is embedded PHP.
        $inlineHtmlPtr = $phpcsFile->findNext(array(T_OPEN_TAG, T_CLOSE_TAG), ($stackPtr + 1));
        if ($inlineHtmlPtr === false) {
            return true;
        }
        
        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $closer = $tokens[$stackPtr]['scope_closer'];
        // @todo
    }
}
