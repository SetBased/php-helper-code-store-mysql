<?php
declare(strict_types=1);

namespace SetBased\Helper\CodeStore;

/**
 * A helper class for automatically generating MySQL compound syntax code with proper indentation.
 */
class MySqlCompoundSyntaxCodeStore extends CodeStore
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function indentationMode(string $line): int
  {
    $mode = 0;

    $line = trim($line);

    if (substr($line, 0, 5)=='begin' || substr($line, 0, 2)=='if' || substr($line, -4, 4)=='loop')
    {
      $mode |= self::C_INDENT_INCREMENT_AFTER;
    }

    if (substr($line, 0, 3)=='end')
    {
      $mode |= self::C_INDENT_DECREMENT_BEFORE;
    }

    return $mode;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
