<?php

class Mole_Sniffs_WhiteSpace_ObjectOperatorIndentSniff implements PHP_CodeSniffer_Sniff
{
    public $indent = 4;

    public function register()
    {
        return array(T_OBJECT_OPERATOR);
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[($stackPtr + 1)]['code'] === T_WHITESPACE) {
            $error = 'Object operator must not contain whitespace after it.';
            $fix = $phpcsFile->addFixableError($error, $stackPtr, 'NoAfterSpace');
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken(($stackPtr + 1), '');
            }
        }

        $varPtr = $phpcsFile->findFirstOnLine(T_WHITESPACE, $stackPtr, true);
        if ($varPtr !== $stackPtr) {
            if ($tokens[($stackPtr - 1)]['code'] === T_WHITESPACE) {
                $error = 'Object operator must not contain whitespace before it.';
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'NoBeforeSpace');
                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken(($stackPtr - 1), '');
                }
            }
            return;
        }

        $varPtr = $phpcsFile->findFirstOnLine(T_WHITESPACE, $stackPtr);
        if ($varPtr === false) {
            return;
        }

        $foundIndent = strlen($tokens[$varPtr]['content']);
        $requiredIndent = $this->indent * ($tokens[$stackPtr]['level'] + 1);
        if ($foundIndent !== $requiredIndent) {
            $error = 'Object operator not indented correctly; expected %s spaces but found %s';
            $data = array(
                $requiredIndent,
                $foundIndent
            );

            $fix = $phpcsFile->addFixableError($error, $stackPtr, 'Incorrect', $data);
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($varPtr, str_repeat(' ', $requiredIndent));
            }
        }
    }
}