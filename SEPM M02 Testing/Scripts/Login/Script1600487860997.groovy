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

WebUI.setText(findTestObject('Object Repository/Page_Login  Tour Management/input_Username_Username'), 'admin')

WebUI.setEncryptedText(findTestObject('Object Repository/Page_Login  Tour Management/input_Password_Password'), 'e12VCJ5A5zM9VveKduLmZw==')

WebUI.click(findTestObject('Object Repository/Page_Login  Tour Management/input_Password_usernameRemember'))

WebUI.click(findTestObject('Object Repository/Page_Login  Tour Management/input_Password_usernameRemember'))

WebUI.click(findTestObject('Object Repository/Page_Login  Tour Management/button_Login'))

