#!/usr/bin/env python
# -*- coding: utf-8 -*-

import re

class ASTGenerator(object):
  """An object that is used to generate abstract syntax tree for arithmetic expressions
  """

  def __init__(self):
    pass

  def __add__(self, other):
    return OperatorNode("+", self, other)


  def __mul__(self, other):
    return OperatorNode("*", self, other)

  def __sub__(self, other):
    return OperatorNode("-", self, other)

  def __div__(self, other):
    return OperatorNode("/", self, other)


class ASTNode(ASTGenerator):
  """Represent a node in abstract syntax tree
  """
  def __init__(self):
    pass


  def evaluate(self):
    return self.evalWithContext({"x": 10})



class NumberNode(ASTNode):
  """Represents a constant number in abstract syntax tree"""

  def __init__(self, number):
    ASTNode.__init__(self)

    self._number = number


  def evalWithContext(self, context):
    return self._number


class OperatorNode(ASTNode):
  """Represents an operator in abstract syntax tree
  """

  def __init__(self, sign, left, right):
    ASTNode.__init__(self)

    self._sign = sign
    self._leftNode = left
    self._rightNode = right


  def evalWithContext(self, context):
    if self._sign == "+":
      arithFun = lambda x, y: x + y
    elif self._sign == "-":
      arithFun = lambda x, y: x - y
    elif self._sign == "*":
      arithFun = lambda x, y: x * y
    elif self._sign == "/":
      arithFun = lambda x, y: x / y
    else:
      raise Exception("Wrong operator")

    return arithFun(self._leftNode.evalWithContext(context), self._rightNode.evalWithContext(context))



class VariableNode(ASTNode):
  """Represents variable in abstract syntax tree
  """

  def __init__(self):
    ASTNode.__init__(self)

  def evalWithContext(self, context):
    return context["x"]



def build_expr_tree(expr_string):
  return eval(\
    re.sub(r"(\d+)", r"NumberNode(\1)", expr_string.replace("x", "VariableNode()"))\
    )


def main():
  expressions = ["x * (x + x) - 10", "x * x", "100 + 100 * 300", "500 + (265 * x)"]
  results = map(lambda x: build_expr_tree(x).evaluate(), expressions)
  print results


if __name__ == "__main__":
  main()
