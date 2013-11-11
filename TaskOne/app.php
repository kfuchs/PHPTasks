<?php
/**
 * This is the best way to duplicate parse_url including all it's quarks.
 *
 * However, probably not the answer your looking for :).
 *
 * @author Kirill <kirill.fuchs@gmail.com>
 * @param string $url The URL to parse. Invalid characters are replaced by _.
 * @param int $component Specify one of PHP_URL_SCHEME, PHP_URL_HOST, PHP_URL_PORT, PHP_URL_USER,
 *      PHP_URL_PASS, PHP_URL_PATH, PHP_URL_QUERY or PHP_URL_FRAGMENT to retrieve just a specific
 *      URL component as a string (except when PHP_URL_PORT is given, in which case the return
 *      value will be an integer).
 * @return mixed On seriously malformed URLs, parse_url may return false
 */
function parse_url_clone($url, $component = -1)
{
    return parse_url($url, $component);
}

/**
 * Another parse_url function without using regex.
 * It's not elegant but should get the job done.
 *
 * The goal is to break down the url into the URI schema defined by RFC 3986.
 * @todo clean me up :).
 * 
 * @author Kirill <kirill.fuchs@gmail.com>
 * @link http://en.wikipedia.org/wiki/URI_scheme
 * @param string $url
 */
function another_parse_url($url)
{
    $char_list = '~!@#$%^&*()_=`[]\\|{};\'":,/<>?';
    
    // Step 1: get the schema
    if (substr($url, 0, 2) !== '//') {
        $scheme = substr($url, 0, strpos($url, ':'));
    
        if (!strpbrk($scheme, $char_list)) {
            $urlParts['scheme'] = $scheme;
            $url = substr($url, strlen($scheme) + 1);
        }
        else {
            $urlPath = $url;
        }
    }
    
    // Step 2: Now we can perform a simple check to see if the URL is too malformed
    if (substr($url, 0, 3) === '///') {
        return false;
    }
    
    // Step 3: Do we have a valid host, if so lets seperate the authority from the url
    if (substr($url, 0, 2) === '//') {
        $url = substr($url, 2);
        
        if (strpos($url, '/')) {
            $urlPath = substr($url, strpos($url, '/'));
            $urlAuth = substr($url, 0, strpos($url, '/'));
        }
        else {
            $urlAuth = $url;
        }
        
    
        // Step 4: Lets seperate the user and pass from the authority
        $loginInfoPos = strpos($urlAuth, '@');
        if ($loginInfoPos) {
            $loginInfo = explode(
                ':',
                substr($urlAuth, 0, $loginInfoPos),
                2)
                ;
                $urlParts['user'] = $loginInfo[0];
                ($loginInfo[1]) ? $urlParts['pass'] = $loginInfo[1] : null;
    
                $urlAuth = substr($urlAuth, $loginInfoPos + 1);
        }
    
        // Step 5: Seperate the port from the host and make sure url is not too malformed.
        $portPos = strrpos($urlAuth, ':');
        if ($portPos) {
            $urlParts['host'] = substr($urlAuth, 0, $portPos);
            $port = substr($urlAuth, $portPos + 1);
    
            if (strlen($port) <= 5 && $port <= '65535') {
                for ($i = 0; $i < strlen($port); $i++) {
                    $portChar = substr($port, $i, 1);
    
                    if (is_numeric($portChar)) {
                        $urlParts['port'] .= $portChar;
                    }
                    else {
                        break;
                    }
                }

                if ($urlParts['port'] <= 0 && strlen($port) > 0) {
                    return false;
                }
                $urlParts['port'] = ltrim($urlParts['port'], '0');
            }
            else {
                return false;
            }
        }
        else {
            $urlParts['host'] = $urlAuth;
        }
    }
    else {
        $urlPath = $url;
    }

    // Step 6: Break apart the fragment, query, and path.
        // Get fragment
    $urlPath = explode('#', $urlPath, 2);
    ($urlPath[1]) 
        ? $urlParts['fragment'] = $urlPath[1] 
        : false;
    
        // Get query
    $urlPath = explode('?', $urlPath[0], 2);
    ($urlPath[1])
        ? $urlParts['query'] = $urlPath[1]
        : false;
        
        // Set what's left to the path
    $urlParts['path'] = $urlPath[0];

    return $urlParts;
}

// Simple test
print_r(parse_url('http://www.google.com'));
print_r(parse_url_clone('http://www.google.com'));
print_r(another_parse_url('http://www.google.com'));

echo '<br><br><br>';

// All parameters test
print_r(parse_url('http://myuser:pass@www.google.com:80/path/to?query=string#withFrag'));
print_r(parse_url_clone('http://myuser:pass@www.google.com:80/path/to?query=string#withFrag'));
print_r(another_parse_url('http://myuser:pass@www.google.com:80/path/to?query=string#withFrag'));

echo '<br><br><br>';

// Return false test
print_r(parse_url('http:///myuser:pass@www.google.com:80/path/to?query=string#withFrag'));
print_r(parse_url_clone('http:///myuser:pass@www.google.com:80/path/to?query=string#withFrag'));
print_r(another_parse_url('http:///myuser:pass@www.google.com:80/path/to?query=string#withFrag'));