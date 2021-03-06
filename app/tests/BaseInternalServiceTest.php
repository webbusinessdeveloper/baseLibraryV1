<?php
/**
 * Created by PhpStorm.
 * User: MacBookEr
 * Date: 4/6/15
 * Time: 4:16 PM
 */
namespace tests;

use Base\MockBaseInternalService as MockBaseInternalService;
use Base\MockBaseModel;
use Base\MockBaseModelWithoutAttributes;
use Illuminate\Support\Facades\DB;

class BaseInternalServiceTest extends \TestCase {

    public function createMockInternalService()
    {
        $mockBaseModel = new MockBaseModel();
        return new MockBaseInternalService($mockBaseModel);
    }

    /**
     * @group baseInternalServiceTests
     * @group internalServiceConstructorTests
     * @group internalServiceFrameworkTests
     */
    public function test_internalServices_must_have_a_model()
    {
        $this->setExpectedException('Exception', 'Model is not set on Internal Service');
        $mockBaseInternalService = new MockBaseInternalService();
    }


    /**
     * @group baseInternalServiceTests
     * @group internalServiceConstructorTests
     * @group internalServiceFrameworkTests
     */
    public function test_models_on_internalServices_must_have_attributes()
    {
        $this->setExpectedException('Exception', 'Attributes not set on Model');
        $mockBaseModelWithoutAttributes = new MockBaseModelWithoutAttributes();
        return new MockBaseInternalService($mockBaseModelWithoutAttributes);
    }


    /**
     * @group baseInternalServiceTests
     * @group internalServiceConstructorTests
     * @group internalServiceFrameworkTests
     */
    public function test_internalService_containing_a_model_with_attributes_does_not_throw_exception()
    {
        $exceptionCatcher = null;
        try{
            $this->createMockInternalService();
        }
        catch(\Exception $e)
        {
            $exceptionCatcher = $e;
        }
        $this->assertNull($exceptionCatcher);
    }


    /**
     *@group baseInternalServiceTests
     */
    public function test_checkModelAcceptsAttributes_method_returns_false_if_model_does_not_accept_attribute_names()
    {
        $badAttributes = [
            'badAttribute' => 'someValue',
            'badAttribute2' => 'someValue'
        ];

        $internalService = $this->createMockInternalService();

        $response = $internalService->checkModelAcceptsAttributes($badAttributes);
        $this->assertFalse($response);
    }

    /**
     *@group baseInternalServiceTests
     */
    public function test_checkModelAcceptsAttributes_method_returns_true_if_model_does_accept_attribute_names()
    {
        $goodAttributes = [
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
        ];

        $internalService = $this->createMockInternalService();

        $response = $internalService->checkModelAcceptsAttributes($goodAttributes);
        $this->assertTrue($response);
    }


    /**
     *@group baseInternalServiceTests
     */
    public function test_getModelClassName_method_returns_className_of_the_model_property_object()
    {
        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $classNameToTestAgainst = '\\' . get_class($mockBaseModel);
        $className = $internalService->getModelClassName();

        $this->assertEquals($classNameToTestAgainst, $className);
    }

    /**
     *@group baseInternalServiceTests
     */
    public function test_createNewModelInstance_method_returns_a_instance_of_the_model_property_object()
    {
        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $modelInstance = $internalService->createNewModelInstance();

        $classNameToTestAgainst = '\\' . get_class($mockBaseModel);
        $classNameToTest = $modelInstance->getSelfClassName();

        $this->assertTrue(is_object($modelInstance) && $classNameToTestAgainst == $classNameToTest);
    }


    /**
     *@group baseInternalServiceTests
     */
    public function test_updateAttributesOnExistingModel_method_returns_model()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $modelInstance = $internalService->createNewModelInstance();

        $updatedModelInstance = $internalService->updateAttributesOnExistingModel($modelInstance, $attributes);

        $this->assertTrue(is_object($updatedModelInstance));
    }

    /**
     *@group baseInternalServiceTests
     */
    public function test_updateAttributesOnExistingModel_method_returns_model_with_correct_attributes()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $modelInstance = $internalService->createNewModelInstance();

        $updatedModelInstance = $internalService->updateAttributesOnExistingModel($modelInstance, $attributes);

        foreach($attributes as $attributeName => $attributeValue)
        {
            $this->assertEquals($attributeValue, $updatedModelInstance->$attributeName);
        }
    }

    /**
     *@group baseInternalServiceTests
     */
    public function test_addAttributesToNewModel_method_returns_a_model()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $newModelWithAttributes = $internalService->addAttributesToNewModel($attributes);

        $this->assertTrue(is_object($newModelWithAttributes));
    }

    /**
     *@group baseInternalServiceTests
     */
    public function test_addAttributesToNewModel_method_returns_a_model_with_correct_attributes()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $newModelWithAttributes = $internalService->addAttributesToNewModel($attributes);

        foreach($attributes as $attributeName => $attributeValue)
        {
            $this->assertEquals($attributeValue, $newModelWithAttributes->$attributeName);
        }
    }


    /**
     * @group baseInternalServiceTests
     */
    public function test_storeEloquentModel_method_returns_model_if_returnInstance_true()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $newModelWithAttributes = $internalService->addAttributesToNewModel($attributes);

        $storeResponse = $internalService->storeEloquentModel($newModelWithAttributes);

        $this->assertTrue(is_object($storeResponse));

        $storeResponse->delete();
    }


    /**
     * @group baseInternalServiceTests
     */
    public function test_storeEloquentModel_method_returns_boolean_if_returnInstance_set_to_false()
    {
        $attributes = [
            'attribute1' => 'value1',
            'attribute2' => 'value2',
            'attribute3' => 'value3',
        ];

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $newModelWithAttributes = $internalService->addAttributesToNewModel($attributes);

        $storeResponse = $internalService->storeEloquentModel($newModelWithAttributes, false);

        $this->assertTrue($storeResponse);

        DB::table('mockBaseModels')->truncate();
    }


    /**
     * @group baseInternalServiceTests
     */
    public function test_isInstanceOfModel_method_returns_false_if_model_passed_is_not_an_object()
    {
        $notAnObject = 'string';

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $response = $internalService->isInstanceOfModel($notAnObject);

        $this->assertFalse($response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_isInstanceOfModel_method_returns_false_if_model_passed_is_not_instance_of_propertyModel()
    {
        $notPropertyModel = new MockBaseModelWithoutAttributes();

        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $response = $internalService->isInstanceOfModel($notPropertyModel);

        $this->assertFalse($response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_isInstanceOfModel_method_returns_true_if_model_passed_is_instance_of_propertyModel()
    {
        $mockBaseModel = new MockBaseModel();
        $internalService = new MockBaseInternalService($mockBaseModel);

        $response = $internalService->isInstanceOfModel($mockBaseModel);

        $this->assertTrue($response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_store_method_sends_error_message_if_attributes_are_not_accepted_by_model()
    {
        $badAttributes = [
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
            'badAttribute' => 'someValue'
        ];

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);
        $response = $mockInternalService->store($badAttributes);

        $this->assertEquals('Attributes are not accepted by model.', $response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_store_method_sends_error_message_if_attributes_fail_child_implemented_validation_methods()
    {
        $goodAttributesBadValue = [
            'attribute1' => 'bad#$#$#',
            'attribute2' => 'goodValue',
            'attribute3' => 'goodValue',
        ];

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);
        $response = $mockInternalService->store($goodAttributesBadValue);

        $this->assertEquals('Attributes failed validation.', $response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_store_method_returns_new_model_on_success()
    {
        $goodAttributesGoodValues = [
            'attribute1' => 'goodValue',
            'attribute2' => 'goodValue',
            'attribute3' => 'goodValue',
        ];

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);
        $response = $mockInternalService->store($goodAttributesGoodValues);

        $this->assertTrue(is_object($response));
        $response->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_store_method_returns_model_of_correct_class_on_success()
    {
        $goodAttributesGoodValues = [
            'attribute1' => 'goodValue',
            'attribute2' => 'goodValue',
            'attribute3' => 'goodValue',
        ];

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);
        $response = $mockInternalService->store($goodAttributesGoodValues);

        $this->assertTrue($mockInternalService->isInstanceOfModel($response));
        $response->delete();
    }






    public function test_store_method_throws_exception_if_attributes_not_returned_from_attributeManipulationLogic_on_child()
    {
        //TODO
    }

    public function test_store_method_returns_instance_on_good_attributes_and_proper_child_hook_implementations()
    {
        //TODO
    }











    /**
     * @group baseInternalServiceTests
     */
    public function test_attemptToRetrieveEloquentModelFromDatabase_method_throws_an_exception_if_model_does_not_exist()
    {
        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $this->setExpectedException('Exception', 'No query results for model [Base\MockBaseModel]');

        $mockInternalService->attemptToRetrieveEloquentModelFromDatabase('aaa');

    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_attemptToRetrieveEloquentModelFromDatabase_method_returns_correct_instance_if_model_exists()
    {
        $newModelInDatabase = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $idToCheck = $newModelInDatabase->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->attemptToRetrieveEloquentModelFromDatabase($idToCheck);

        $this->assertEquals($idToCheck, $response->id);
        $newModelInDatabase->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_attemptToRetrieveEloquentModelFromDatabase_method_returns_correct_class_if_model_exists()
    {
        $newModelInDatabase = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $idToCheck = $newModelInDatabase->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->attemptToRetrieveEloquentModelFromDatabase($idToCheck);
        $this->assertTrue($mockInternalService->isInstanceOfModel($response));

        $newModelInDatabase->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_checkIfModelExists_method_returns_false_if_model_with_given_id_does_not_exist()
    {
        $fakeId = 'aaa';

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->checkIfModelExists($fakeId);
        $this->assertFalse($response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_checkIfModelExists_method_returns_true_if_model_with_given_id_does_exists_in_database_table()
    {
        $newModelInDatabase = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDatabase->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->checkIfModelExists($id);

        $this->assertTrue($response);
        $newModelInDatabase->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_show_method_returns_error_message_if_model_with_given_id_does_not_exists()
    {
        $badId = 'aaa';

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->show($badId);
        $this->assertEquals('No model by id: '. $badId, $response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_show_method_returns_correct_class_if_model_with_given_id_exists()
    {
        $newModelInDatabase = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDatabase->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->show($id);
        $this->assertTrue($mockInternalService->isInstanceOfModel($response));

        $newModelInDatabase->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_show_method_returns_correct_instance_if_model_with_given_id_exists()
    {
        $newModelInDatabase = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDatabase->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->show($id);
        $this->assertEquals($id, $response->id);

        $newModelInDatabase->delete();
    }


    /**
     * @group baseInternalServiceTests
     */
    public function test_update_method_returns_error_message_if_model_does_not_accept_attributes()
    {
        $badAttributes = [
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
            'badAttribute' => 'someValue'
        ];

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $newMockInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newMockInDB->id;

        $response = $mockInternalService->update($id, $badAttributes);
        $this->assertEquals('Attributes are not accepted by model.', $response);

        $newMockInDB->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_update_method_returns_error_message_if_attributes_fail_validation()
    {
        $goodAttributesBadValue = [
            'attribute1' => 'bad#(@#(*@',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ];

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $newMockInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newMockInDB->id;

        $response = $mockInternalService->update($id, $goodAttributesBadValue);
        $this->assertEquals('Attributes failed validation.', $response);

        $newMockInDB->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_update_method_returns_error_message_if_model_does_not_exists()
    {
        $badId = 'aaa';
        $goodAttributesAndValues = [
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ];

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->update($badId, $goodAttributesAndValues);
        $this->assertEquals('No model by id: '. $badId, $response);
    }


    /**
     * @group baseInternalServiceTests
     */
    public function test_update_method_returns_correct_class_if_model_exists_and_attributes_are_valid()
    {
        $newModelInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDB->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $newAttributes = [
            'attribute1' => 'newValue1',
            'attribute2' => 'newValue2',
            'attribute3' => 'newValue3',
        ];

        $response = $mockInternalService->update($id, $newAttributes);
        $this->assertTrue($mockInternalService->isInstanceOfModel($response));

        $newModelInDB->delete();
    }


    /**
     * @group baseInternalServiceTests
     */
    public function test_update_method_returns_correct_instance_if_model_exists_and_attributes_are_valid()
    {
        $newModelInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDB->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $newAttributes = [
            'attribute1' => 'newValue1',
            'attribute2' => 'newValue2',
            'attribute3' => 'newValue3',
        ];

        $response = $mockInternalService->update($id, $newAttributes);
        $this->assertEquals($id, $response->id);

        $newModelInDB->delete();
    }


    /**
     * @group baseInternalServiceTests
     */
    public function test_update_method_returns_model_with_updated_attributes_if_model_exists_and_attributes_are_valid()
    {
        $newModelInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDB->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $newAttributes = [
            'attribute1' => 'newValue1',
            'attribute2' => 'newValue2',
            'attribute3' => 'newValue3',
        ];

        $response = $mockInternalService->update($id, $newAttributes);
        $this->assertEquals($newAttributes['attribute1'], $response->attribute1);

        $newModelInDB->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_update_method_saves_updates_in_database()
    {
        $newModelInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDB->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $newAttributes = [
            'attribute1' => 'newValue1',
            'attribute2' => 'newValue2',
            'attribute3' => 'newValue3',
        ];

        $mockInternalService->update($id, $newAttributes);

        $modelFromDB = MockBaseModel::find($id);
        $this->assertEquals($newAttributes['attribute1'], $modelFromDB->attribute1);

        $newModelInDB->delete();
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_deleteEloquentModel_method_removes_identified_model_from_database()
    {
        $newModelInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDB->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $mockInternalService->deleteEloquentModel($newModelInDB);
        $response = MockBaseModel::find($id);

        $this->assertNull($response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_deleteEloquentModel_method_returns_true_on_success()
    {
        $newModelInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->deleteEloquentModel($newModelInDB);

        $this->assertTrue($response);
    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_destroy_method_returns_error_message_if_model_does_not_exist()
    {
        $badId = 'aaa';

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->destroy($badId);

        $this->assertEquals('No model by id: '. $badId, $response);

    }

    /**
     * @group baseInternalServiceTests
     */
    public function test_destroy_method_returns_true_on_success()
    {
        $newModelInDB = MockBaseModel::create([
            'attribute1' => 'someValue',
            'attribute2' => 'someValue',
            'attribute3' => 'someValue',
        ]);

        $id = $newModelInDB->id;

        $mockBaseModel = new MockBaseModel();
        $mockInternalService = new MockBaseInternalService($mockBaseModel);

        $response = $mockInternalService->destroy($id);

        $this->assertTrue($response);
    }


}
