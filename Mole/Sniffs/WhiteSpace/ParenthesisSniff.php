<?php
class Mole_Sniffs_WhiteSpace_ParenthesisSniff implements PHP_CodeSniffer_Sniff
{
    public function register()
    {
        return array(
            T_OPEN_PARENTHESIS,
            T_CLOSE_PARENTHESIS
        );
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_owner']) === true) {
            return;
        }

        if ($tokens[$stackPtr]['code'] === T_OPEN_PARENTHESIS) {
            $next = ($stackPtr + 1);
            $found = 0;
            if ($tokens[$next]['code'] === T_WHITESPACE) {
                $found = strlen(str_replace("\n", '', $tokens[$next]['content']));
            }

            if ($found > 0) {
                $error = 'Expected 0 spaces after open parenthesis; %s found.';
                $data = array($found);
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpaceAfterOpenParenthesis', $data);
                if ($fix === true) {
                    if (strpos($tokens[$next]['content'], $phpcsFile->eolChar) !== false) {
                        $phpcsFile->fixer->replaceToken($next, $phpcsFile->eolChar);
                    } else {
                        $phpcsFile->fixer->replaceToken($next, '');
                    }
                }
            }
        } elseif ($tokens[$stackPtr]['code'] === T_CLOSE_PARENTHESIS) {
            $found = 0;
            $prev = ($stackPtr - 1);
            if ($tokens[$prev]['code'] === T_WHITESPACE
                && $tokens[$prev]['line'] === $tokens[$stackPtr]['line']
                && $phpcsFile->findFirstOnLine(T_WHITESPACE, $stackPtr, true) !== $stackPtr
            ) {
                $found = strlen($tokens[$prev]['content']);
            }

            if ($found > 0) {
                $error = 'Expected 0 spaces before close parenthesis; %s found.';
                $data = array($found);
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpaceBeforeCloseParenthesis', $data);
                if ($fix) {
                    $phpcsFile->fixer->replaceToken($prev, '');
                }
            }
        }
    }
}
