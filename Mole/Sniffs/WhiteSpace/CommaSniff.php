<?php
class Mole_Sniffs_WhiteSpace_CommaSniff implements PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return array(
            T_COMMA
        );
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $prev = ($stackPtr - 1);
        $found = 0;
        if ($tokens[$prev]['code'] === T_WHITESPACE) {
            $found = strlen($tokens[$prev]['content']);
        }

        if ($found > 0) {
            $error = 'Expected 0 spaces before comma; %s found.';
            $data = array($found);
            $fix = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceBeforeComma', $data);
            if ($fix) {
                $phpcsFile->fixer->replaceToken($prev, '');
            }
        }

        $next = ($stackPtr + 1);
        if ($tokens[$next]['code'] !== T_WHITESPACE) {
            $found = 0;
        } else {
            $found = strlen($tokens[$next]['content']);
        }

        if ($found !== 1) {
            $error = 'Expected 0 spaces after comma; %s found.';
            $data = array($found);
            $fix = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpaceAfterComma', $data);
            if ($fix === true) {
                if ($found === 0) {
                    $phpcsFile->fixer->addContent($stackPtr, ' ');
                } else {
                    if ($found > 1 && strpos($tokens[$next]['content'], $phpcsFile->eolChar) !== false) {
                        $phpcsFile->fixer->replaceToken($next, $phpcsFile->eolChar);
                    } else {
                        $phpcsFile->fixer->replaceToken($next, ' ');
                    }
                }
            }
        }
    }
}
