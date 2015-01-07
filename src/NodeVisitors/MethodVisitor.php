<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;
use PhpParser;
use PhpParser\Node\Stmt;

class MethodVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;
    private $factory;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\ClassMethod) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $this->scope->startMethod ($obj->getName());
            $this->data->saveScopePosition ($this->scope->getScopeName(), $obj->line);

            // report non public methods
            if ($node->isPrivate()) 
            {
                $this->data->addIssue ($obj->line, 'private_method', $this->scope->getScopeName(), $obj->getName());
            }
            elseif ($node->isProtected()) 
            {
                $this->data->addIssue ($obj->line, 'protected_method', $this->scope->getScopeName(), $obj->getName());
            }

            // report final methods
            if ($node->isFinal()) 
            {
                $this->data->addIssue ($obj->line, 'final_method', $this->scope->getScopeName(), $obj->getName());
            }
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // end of method or global function
        if ($node instanceof Stmt\ClassMethod) 
        {
            $this->scope->endMethod();
        }
    }
}
