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

    if (str_starts_with($line, 'begin') || str_starts_with($line, 'if') || str_ends_with($line, 'loop'))
    {
      $mode |= self::C_INDENT_INCREMENT_AFTER;
    }

    if (str_starts_with($line, 'end'))
    {
      $mode |= self::C_INDENT_DECREMENT_BEFORE;
    }

    return $mode;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
