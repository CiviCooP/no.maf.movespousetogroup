no.maf.movespousetogroup
========================

When a spouse relationship exists between two contacts and one of the contacts 
joins or leaves a donor journey group the other contacts also joins or leaves 
this group. 

This is because at the MAF they wanted to make sure spouses are in the same donor
journey group.

As you have guessed this is very specific MAF extension. 

Technical details
-----------------

The extension utilize the civicrm_post_hook for *GroupContact*. Each time the 
hook is invoked for the object *GroupContact* the spouse relationships are 
retrieved and the same action (add/remove/rejoin/delete) are executed for the spouses. 