<?php

namespace App\Services;

class MathEvaluator
{
    /**
     * Evaluate a mathematical expression and return the float result.
     *
     * @param string $expression
     * @return float
     * @throws \InvalidArgumentException
     */
    public function evaluate(string $expression): float
    {
        $expression = trim($expression);
        if ($expression === '') {
            return 0.0;
        }

        // Validate that only allowed characters are present
        if (!preg_match('/^[0-9+\-*\/%().\s]+$/', $expression)) {
            throw new \InvalidArgumentException("Formula contains invalid characters.");
        }

        $tokens = $this->tokenize($expression);
        if (empty($tokens)) {
            return 0.0;
        }

        $rpn = $this->parseToRpn($tokens);
        return $this->evaluateRpn($rpn);
    }

    /**
     * Tokenize the mathematical expression.
     *
     * @param string $expression
     * @return array
     */
    private function tokenize(string $expression): array
    {
        // Extract digits (including decimals), operators, and parentheses
        preg_match_all('/[0-9]+(?:\.[0-9]+)?|[+\-*\/%()]/', $expression, $matches);
        return $matches[0] ?? [];
    }

    /**
     * Convert an array of infix tokens to postfix (Reverse Polish Notation) using Shunting-yard.
     *
     * @param array $tokens
     * @return array
     * @throws \InvalidArgumentException
     */
    private function parseToRpn(array $tokens): array
    {
        $outputQueue = [];
        $operatorStack = [];

        $operators = [
            '+' => ['precedence' => 2, 'associativity' => 'left'],
            '-' => ['precedence' => 2, 'associativity' => 'left'],
            '*' => ['precedence' => 3, 'associativity' => 'left'],
            '/' => ['precedence' => 3, 'associativity' => 'left'],
            '%' => ['precedence' => 3, 'associativity' => 'left'],
            'u-' => ['precedence' => 4, 'associativity' => 'right'],
            'u+' => ['precedence' => 4, 'associativity' => 'right'],
        ];

        $prevToken = null;

        foreach ($tokens as $token) {
            if (is_numeric($token)) {
                $outputQueue[] = (float) $token;
            } elseif ($token === '+' || $token === '-') {
                // Determine if this is a unary operator
                $isUnary = ($prevToken === null || $prevToken === '(' || isset($operators[$prevToken]));
                $op = $isUnary ? 'u' . $token : $token;

                while (!empty($operatorStack)) {
                    $top = end($operatorStack);
                    if ($top === '(') {
                        break;
                    }

                    $op1 = $operators[$op];
                    $op2 = $operators[$top];

                    if (($op1['associativity'] === 'left' && $op1['precedence'] <= $op2['precedence']) ||
                        ($op1['associativity'] === 'right' && $op1['precedence'] < $op2['precedence'])) {
                        $outputQueue[] = array_pop($operatorStack);
                    } else {
                        break;
                    }
                }
                $operatorStack[] = $op;
            } elseif ($token === '*' || $token === '/' || $token === '%') {
                $op = $token;
                while (!empty($operatorStack)) {
                    $top = end($operatorStack);
                    if ($top === '(') {
                        break;
                    }

                    $op1 = $operators[$op];
                    $op2 = $operators[$top];

                    if (($op1['associativity'] === 'left' && $op1['precedence'] <= $op2['precedence']) ||
                        ($op1['associativity'] === 'right' && $op1['precedence'] < $op2['precedence'])) {
                        $outputQueue[] = array_pop($operatorStack);
                    } else {
                        break;
                    }
                }
                $operatorStack[] = $op;
            } elseif ($token === '(') {
                $operatorStack[] = $token;
            } elseif ($token === ')') {
                $matched = false;
                while (!empty($operatorStack)) {
                    $top = array_pop($operatorStack);
                    if ($top === '(') {
                        $matched = true;
                        break;
                    }
                    $outputQueue[] = $top;
                }
                if (!$matched) {
                    throw new \InvalidArgumentException("Mismatched parentheses: closing parenthesis has no opening parenthesis.");
                }
            } else {
                throw new \InvalidArgumentException("Invalid token in formula: " . $token);
            }

            $prevToken = $token;
        }

        while (!empty($operatorStack)) {
            $top = array_pop($operatorStack);
            if ($top === '(') {
                throw new \InvalidArgumentException("Mismatched parentheses: opening parenthesis has no closing parenthesis.");
            }
            $outputQueue[] = $top;
        }

        return $outputQueue;
    }

    /**
     * Evaluate an RPN queue.
     *
     * @param array $rpn
     * @return float
     * @throws \InvalidArgumentException
     */
    private function evaluateRpn(array $rpn): float
    {
        $stack = [];

        foreach ($rpn as $token) {
            if (is_float($token) || is_int($token)) {
                $stack[] = (float) $token;
            } else {
                if ($token === 'u-') {
                    if (empty($stack)) {
                        throw new \InvalidArgumentException("Invalid expression structure for unary minus.");
                    }
                    $val = array_pop($stack);
                    $stack[] = -$val;
                } elseif ($token === 'u+') {
                    if (empty($stack)) {
                        throw new \InvalidArgumentException("Invalid expression structure for unary plus.");
                    }
                    // Unary plus is a no-op, value remains on stack
                } else {
                    if (count($stack) < 2) {
                        throw new \InvalidArgumentException("Invalid expression structure for operator: " . $token);
                    }
                    $b = array_pop($stack);
                    $a = array_pop($stack);

                    switch ($token) {
                        case '+':
                            $stack[] = $a + $b;
                            break;
                        case '-':
                            $stack[] = $a - $b;
                            break;
                        case '*':
                            $stack[] = $a * $b;
                            break;
                        case '/':
                            if ($b == 0.0) {
                                throw new \InvalidArgumentException("Division by zero error.");
                            }
                            $stack[] = $a / $b;
                            break;
                        case '%':
                            if ($b == 0.0) {
                                throw new \InvalidArgumentException("Division by zero (modulo) error.");
                            }
                            $stack[] = fmod($a, $b);
                            break;
                        default:
                            throw new \InvalidArgumentException("Unknown operator: " . $token);
                    }
                }
            }
        }

        if (count($stack) !== 1) {
            throw new \InvalidArgumentException("Invalid expression format.");
        }

        return array_pop($stack);
    }
}
