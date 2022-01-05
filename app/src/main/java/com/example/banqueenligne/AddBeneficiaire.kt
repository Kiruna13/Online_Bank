package com.example.banqueenligne

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle

class AddBeneficiaire : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_add_beneficiaire)

        //actionbar
        val actionbar = supportActionBar
        //set actionbar title
        actionbar!!.title = "Nouveau bénéficiaire"
        //set back button
        actionbar.setDisplayHomeAsUpEnabled(true)
        actionbar.setHomeAsUpIndicator(R.drawable.ic_action_close);
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }
}