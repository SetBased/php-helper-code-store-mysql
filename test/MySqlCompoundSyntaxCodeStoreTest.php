<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Helper\CodeStore\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Helper\CodeStore\MySqlCompoundSyntaxCodeStore;

/**
 * Test cases for class MySqlCompoundSyntaxCodeStore.
 */
class MySqlCompoundSyntaxCodeStoreTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test indentation levels.
   */
  public function testIndentationLevel1()
  {
    $store = new MySqlCompoundSyntaxCodeStore();

    $store->append('create trigger trg_usr_insert`');
    $store->append('begin');
    $store->append('if (abc_g_skip_aut_user is null) then');
    $store->append('if (@audit_uuid is null) then');
    $store->append('set @audit_uuid = uuid_short();');
    $store->append('end if;');
    $store->append('set @audit_rownum = ifnull(@audit_rownum, 0) + 1;');
    $store->append('insert into `mmm_audit`.`AUT_USER`(`audit_timestamp`,`audit_uuid`,`audit_rownum`)');
    $store->append('values(sysdate(),@audit_uuid,@audit_rownum);');
    $store->append('end if;');
    $store->append('end');

    $expected = <<< EOL
create trigger trg_usr_insert`
begin
  if (abc_g_skip_aut_user is null) then
    if (@audit_uuid is null) then
      set @audit_uuid = uuid_short();
    end if;
    set @audit_rownum = ifnull(@audit_rownum, 0) + 1;
    insert into `mmm_audit`.`AUT_USER`(`audit_timestamp`,`audit_uuid`,`audit_rownum`)
    values(sysdate(),@audit_uuid,@audit_rownum);
  end if;
end

EOL;

    $code = $store->getCode();

    $this->assertEquals($expected, $code);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test indentation levels with loop.
   */
  public function testIndentationLevel2()
  {
    $store = new MySqlCompoundSyntaxCodeStore();

    $store->append('create procedure abc_blob_insert_blob()');
    $store->append('modifies sql data');
    $store->append('begin');
    $store->append('// More code');
    $store->append('open c_data;');
    $store->append('loop1: loop');
    $store->append('set l_done = false;');
    $store->append('fetch c_data');
    $store->append('into  l_bdt_id,');
    $store->append('      l_bdt_data', false);
    $store->append(';');
    $store->append('if (l_done) then');
    $store->append('close c_data;');
    $store->append('leave loop1;');
    $store->append('end if;');
    $store->append('');
    $store->append('if (p_bdt_data=l_bdt_data) then');
    $store->append('close c_data;');
    $store->append('leave loop1;');
    $store->append('end if;');
    $store->append('end loop;');
    $store->append('// More code');
    $store->append('end');

    $expected = <<< EOL
create procedure abc_blob_insert_blob()
modifies sql data
begin
  // More code
  open c_data;
  loop1: loop
    set l_done = false;
    fetch c_data
    into  l_bdt_id,
          l_bdt_data
    ;
    if (l_done) then
      close c_data;
      leave loop1;
    end if;

    if (p_bdt_data=l_bdt_data) then
      close c_data;
      leave loop1;
    end if;
  end loop;
  // More code
end

EOL;

    $code = $store->getCode();

    $this->assertEquals($expected, $code);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
