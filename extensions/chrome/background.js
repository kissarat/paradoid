function createUUID() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
  }
  
let agent;

chrome.runtime.onInstalled.addListener(function() {
    chrome.storage.local.get(['agent'], function(result) {
        if (result.key) {
            agent = result.key;
        } else {
            chrome.storage.local.set({ agent }, function() {
            agent = createUUID();
            });
        }
    });
});

chrome.webNavigation.onCompleted.addListener(async function({ url }) {
    await fetch('http://localhost:8765/save.php', {
        method: 'POST',
        body: JSON.stringify({ url, agent }),
        headers: {
            'content-type': 'application/json'
        }
    })
});
