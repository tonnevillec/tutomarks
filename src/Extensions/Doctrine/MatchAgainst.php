<?php

namespace App\Extensions\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class MatchAgainst extends FunctionNode
{
    protected ?array $pathExp = null;
    protected ?string $against = null;
    protected bool $booleanMode = false;
    protected bool $queryExpansion = false;

    public function parse(Parser $parser)
    {
        // match
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        // first Path Expression is mandatory
        $this->pathExp = [];
        $this->pathExp[] = $parser->StateFieldPathExpression();
        // Subsequent Path Expressions are optional
        $lexer = $parser->getLexer();
        while ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->pathExp[] = $parser->StateFieldPathExpression();
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        // against
        if ('against' !== strtolower($lexer->lookahead['value'])) {
            $parser->syntaxError('against');
        }
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->against = $parser->StringPrimary();
        if ('boolean' === strtolower($lexer->lookahead['value'])) {
            $parser->match(Lexer::T_IDENTIFIER);
            $this->booleanMode = true;
        }
        if ('expand' === strtolower($lexer->lookahead['value'])) {
            $parser->match(Lexer::T_IDENTIFIER);
            $this->queryExpansion = true;
        }
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $walker)
    {
        $fields = [];
        foreach ($this->pathExp as $pathExp) {
            $fields[] = $pathExp->dispatch($walker);
        }
        $against = $walker->walkStringPrimary($this->against)
            .($this->booleanMode ? ' IN BOOLEAN MODE' : '')
            .($this->queryExpansion ? ' WITH QUERY EXPANSION' : '');

        return sprintf('MATCH (%s) AGAINST (%s)', implode(', ', $fields), $against);
    }
}
