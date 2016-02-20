# ![ImagicalMine](http://i.imgur.com/dPwr1cT.jpg)

# ImagicalMine Contribution Guidelines

## I have a question
* You can ask directly to _[@ImagicalTeam](https://twitter.com/ImagicalTeam)_ in Twitter, but don't expect an immediate reply.
* You may use our [Forum](http://forums.imagicalcorp.ml) to ask questions.
* You may use our Gitter chatroom to ask questions and comment or socialise.
* We do not accept questions or support requests in our issue tracker.

## Creating an Issue
 - Important: Make sure you do not use the issue tracker as a chat room.
 - First, use the [Issue Search](https://github.com/ImagicalCorp/ImagicalMinesearch?ref=cmdform&type=Issues) to check if anyone has reported it.
 - If your issue is related to a plugin, you must contact their original author instead of reporting it here.
 - If your issue is related to an ImagicalMine official plugin, you must create an issue on that specific repository.
 - **Support requests are not bugs.** Issues such as "How do I do this" are not bugs and are closed as soon as a collaborator spots it. They are referred to our Forum to seek assistance.
 - **No generic titles** such as "Question", "Help", "Crash Report" etc. If an issue has a generic title they will either be closed on the spot, or a collaborator will edit it to describe the actual symptom.
 - Information must be provided in the issue body, not in the title. No tags are allowed in the title, and do not change the title if the issue has been solved.
 - Similarly, no generic issue reports. It is the issue submitter's responsibility to provide us an issue that is **trackable, debuggable, reproducible, reported professionally and is an actual bug**. If you do not provide us with a summary or instructions on how to reproduce the issue, it is a support request until the actual bug has been found and therefore the issue is closed.

## Contributing Code
* Use the [Pull Request](https://github.com/ImagicalCorp/ImagicalMine/pull/new) system, your request will be checked and discussed.
* __Create a single branch for that pull request__
* The code must be clear and written in English, comments included.
* Use descriptive commit titles

**Thanks for contributing to ImagicalMine!**

## Bug Tracking for Collaborators

### Labels
To provide a concise bug tracking environment, prevent the issue tracker from over flowing and to keep support requests out of the bug tracker, ImagicalMine uses a label scheme a bit different from the default GitHub Issues labels.

ImagicalMine uses GitHub Issues Labels. For future reference, labels must not be longer than 15 letters.

#### Categories
Category labels are prefixed by `C:`. Multiple category labels may be applied to a single issue(but try to keep this to a minimum and do not overuse category labels).
 - C: Core - This label is applied when the bug results in a fatal crash, or is related to neither Gameplay nor Plugin API.
 - C: Gameplay - This label is applied when the bug effects the gameplay.
 - C: API - This label is applied when the bug effects the Plugin API.

#### Pull Requests
Pull Requests are prefixed by `PR:`. Only one label may be applied for a Pull Request.
 - PR: Bug Fix - This label is applied when the Pull Request fixes a bug. 
 - PR: Contribution - This label is applied when the Pull Request contributes code to ImagicalMine such as a new feature or an improvement.
 - PR: RFC - Request for Comments

#### Status
Status labels show the status of the issue. Multiple status labels may be applied.
 - Duplicated - This label is applied when the bug has been reproduced, or multiple people are reporting the same issue and symptoms in which case it is automatically assumed that the bug has been reproduced in different environments.
 - Possibly Fixed - This label is applied when the cause of the bug has been found.
 - Critical - This label is applied when the bug is easy to fix, or if the scale of the bug is global.
 - Won't Fix - This label is applied if the bug has been decided not be fixed for some reason. e.g. when the bug benefits gameplay. *This label may only be applied to a closed issue.*

#### Miscellaneous
Miscellaneous labels are labels that show status not related to debugging that bug. The To-Do label and the Mojang label may not be applied to a single issue at the same time.
 - To-Do - This label is applied when the issue is not a bug, but a feature request or a list of features to be implemented that count towards a milestone.
 - Mojang - This label is applied when the issue is suspected of being caused by the Minecraft: Pocket Edition client, but has not been confirmed.
 - Invalid - This label is applied when the issue is reporting a false bug that works as intended, a support request, etc. *This label may only be applied to a closed issue.*

### Closing Issues
To keep the bug tracker clear of non-related issues and to prevent it from overflowing, **issues must be closed as soon as possible** (This may sound unethical, but it is MUCH better than having the BUG TRACKER filled with SUPPORT REQUESTS and "I NEED HELP").

If an issue does not conform to the "Creating an Issue" guidelines above, the issue should be closed.
