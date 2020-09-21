import static com.kms.katalon.core.checkpoint.CheckpointFactory.findCheckpoint
import static com.kms.katalon.core.testcase.TestCaseFactory.findTestCase
import static com.kms.katalon.core.testdata.TestDataFactory.findTestData
import static com.kms.katalon.core.testobject.ObjectRepository.findTestObject
import static com.kms.katalon.core.testobject.ObjectRepository.findWindowsObject
import com.kms.katalon.core.checkpoint.Checkpoint as Checkpoint
import com.kms.katalon.core.cucumber.keyword.CucumberBuiltinKeywords as CucumberKW
import com.kms.katalon.core.mobile.keyword.MobileBuiltInKeywords as Mobile
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import com.kms.katalon.core.testcase.TestCase as TestCase
import com.kms.katalon.core.testdata.TestData as TestData
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.webservice.keyword.WSBuiltInKeywords as WS
import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.windows.keyword.WindowsBuiltinKeywords as Windows
import internal.GlobalVariable as GlobalVariable
import org.openqa.selenium.Keys as Keys

WebUI.openBrowser('')

WebUI.navigateToUrl('https://121.200.18.218:4433/m02/login.php')

WebUI.setText(findTestObject('Object Repository/Add new tour/Page_Login  Tour Management/input_Username_Username'), 'admin')

WebUI.setEncryptedText(findTestObject('Object Repository/Add new tour/Page_Login  Tour Management/input_Password_Password'), 
    'e12VCJ5A5zM9VveKduLmZw==')

WebUI.sendKeys(findTestObject('Object Repository/Add new tour/Page_Login  Tour Management/input_Password_Password'), Keys.chord(
        Keys.ENTER))

WebUI.click(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/input_Tour Name_TourName'))

WebUI.click(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/a_Tour Management'))

WebUI.click(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/a_Add New Tour'))

WebUI.setText(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/input_Tour Name_TourName'), 
    'Tour Test 2')

WebUI.selectOptionByValue(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/select_Select Tour Type                    _c7a365'), 
    '1', true)

WebUI.selectOptionByValue(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/select_Select First Location               _1fa2c2'), 
    '2', true)

WebUI.selectOptionByValue(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/select_Select Second Location              _88b3a5'), 
    '7', true)

WebUI.selectOptionByValue(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/select_Select Third Location               _1afd60'), 
    '3', true)

WebUI.click(findTestObject('Object Repository/Add new tour/Page_Add Tour  Tour Management/button_Submit'))

