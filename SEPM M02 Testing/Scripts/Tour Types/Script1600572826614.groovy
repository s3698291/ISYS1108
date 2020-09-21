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

WebUI.navigateToUrl('https://121.200.18.218:4433/m02/login')

WebUI.setText(findTestObject('Object Repository/Tour Types/Page_Login  Tour Management/input_Username_Username'), 'admin')

WebUI.setEncryptedText(findTestObject('Object Repository/Tour Types/Page_Login  Tour Management/input_Password_Password'), 
    'e12VCJ5A5zM9VveKduLmZw==')

WebUI.sendKeys(findTestObject('Object Repository/Tour Types/Page_Login  Tour Management/input_Password_Password'), Keys.chord(
        Keys.ENTER))

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Add Tour  Tour Management/a_Tour Management'))

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Add Tour  Tour Management/a_Add New Tour Type'))

WebUI.setText(findTestObject('Object Repository/Tour Types/Page_Add Tour Type  Tour Management/input_Tour Type Name_TourType'), 
    'TourTest2')

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Add Tour Type  Tour Management/button_Submit'))

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Add Tour Type  Tour Management/a_Tour Management'))

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Add Tour Type  Tour Management/a_Manage Existing Tour Type'))

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Manage Tour Type  Tour Management/a_Edit'))

WebUI.setText(findTestObject('Object Repository/Tour Types/Page_Edit Tour Type  Tour Management/input_Tour Type_TourType'), 
    'TourTest2edited')

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Edit Tour Type  Tour Management/button_Save Changes'))

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Manage Tour Type  Tour Management/a_Remove'))

WebUI.click(findTestObject('Object Repository/Tour Types/Page_Manage Tour Type  Tour Management/a_Recover'))

WebUI.closeBrowser()

