package com.mycompany.app;

import org.junit.Before;
import org.junit.Test;
import org.junit.After;
import static org.junit.Assert.*;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

/**
 * Integration UI test for PHP App.
 */
public class AppTest
{
    WebDriver driver; 
    WebDriverWait wait; 
    String url = "http://172.17.0.2";
    String validSearchTerm = "example";
    String invalidSearchTerm = "invalidterm";

    @Before
    public void setUp() { 
        driver = new HtmlUnitDriver(); 
        wait = new WebDriverWait(driver, 10); 
    } 

    @After
    public void tearDown() { 
        driver.quit(); 
    }	 

    @Test
    public void testSearchWithValidTerm() throws InterruptedException { 
        System.out.println("Navigating to URL: " + url);
        driver.get(url);
        System.out.println("Waiting for the page to load");
        wait.until(ExpectedConditions.titleContains("Search Page")); 
        System.out.println("Page loaded, proceeding with test steps");

        // Enter input
        driver.findElement(By.name("search_term")).sendKeys(validSearchTerm);
        // Click submit
        driver.findElement(By.name("submit")).click(); // Use click() instead of submit()

        // Check result 
        By resultMsgLocator = By.className("result-msg");
        WebElement resultElement = wait.until(ExpectedConditions.visibilityOfElementLocated(resultMsgLocator));
        String resultMessage = resultElement.getText();
        String expectedResult = "Search Results for \"" + validSearchTerm + "\"";
        assertTrue(resultMessage.contains(expectedResult));
    }

    @Test
    public void testSearchWithInvalidTerm() throws InterruptedException { 
        // Get web page
        driver.get(url);
        // Wait until page is loaded or timeout error
        wait.until(ExpectedConditions.titleContains("Search Page")); 

        // Enter input
        driver.findElement(By.name("search_term")).sendKeys(invalidSearchTerm);
        // Click submit
        driver.findElement(By.name("submit")).click(); // Use click() instead of submit()

        // Check result
        By resultMsgLocator = By.className("result-msg");
        WebElement resultElement = wait.until(ExpectedConditions.visibilityOfElementLocated(resultMsgLocator));
        String resultMessage = resultElement.getText();
        String expectedResult = "No results found for '" + invalidSearchTerm + "'";
        assertTrue(resultMessage.contains(expectedResult));
    }
}
